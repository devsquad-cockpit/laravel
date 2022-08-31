<?php

namespace Cockpit\Reports;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Cockpit\Models\Occurrence;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class OccurrencesReport
{
    protected Carbon $from;

    protected Carbon $to;

    protected array $labels = [];

    public function __construct(Carbon $from, Carbon $to)
    {
        $this->from = $from;
        $this->to   = $to;

        $this->generateLabels();
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getUnsolvedErrors(): array
    {
        return $this->getItems(true);
    }

    public function getTotalErrors(): array
    {
        return $this->getItems();
    }

    protected function generateLabels(): void
    {
        $period = new CarbonPeriod($this->from, $this->to);

        foreach ($period as $day) {
            $this->labels[] = $day->format('y/m/d');
        }
    }

    protected function getItems(bool $unsolvedErrors = false): array
    {
        $data = Occurrence::selectRaw('date(occurrences.created_at) as error_date, count(*) as total')
            ->when($unsolvedErrors, function (Builder $query) {
                $query->join('errors', function (JoinClause $join) {
                    $join->on('errors.id', '=', 'occurrences.error_id')
                        ->whereNull('resolved_at');
                });
            })
            ->whereBetween('occurrences.created_at', [$this->from, $this->to])
            ->groupBy('error_date')
            ->orderBy('error_date')
            ->get()
            ->each(fn (Occurrence $occurrence) => $occurrence->error_date = Carbon::parse($occurrence->error_date)->format('y/m/d'))
            ->pluck('total', 'error_date')
            ->toArray();

        return array_values(
            $this->addMissingItems($data)
        );
    }

    protected function addMissingItems(array $data): array
    {
        foreach ($this->labels as $label) {
            if (array_key_exists($label, $data)) {
                continue;
            }

            $data[$label] = 0;
        }

        ksort($data);

        return $data;
    }
}
