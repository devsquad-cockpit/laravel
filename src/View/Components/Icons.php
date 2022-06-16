<?php

namespace Cockpit\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use ReflectionClass;

class Icons extends Component
{
    public $icon = null;

    public $class = null;

    public $outline = null;

    public $fill = null;

    /** @suppressWarnings(PHPMD.ExcessiveParameterList) */
    public function __construct(
        ?string $app = null,
        ?string $arrowDown = null,
        ?string $arrowLeft = null,
        ?string $arrowRight = null,
        ?string $calendar = null,
        ?string $chevronDown = null,
        ?string $chevronUp = null,
        ?string $chevronUpDown = null,
        ?string $cog = null,
        ?string $document = null,
        ?string $github = null,
        ?string $group = null,
        ?string $lightBulb = null,
        ?string $lightningBolt = null,
        ?string $link = null,
        ?string $menu = null,
        ?string $puzzle = null,
        ?string $search = null,
        ?string $upload = null,
        ?string $x = null,
        ?string $icon = null,
        ?string $class = null,
        ?bool $outline = null,
        ?bool $fill = true
    ) {
        $this->class   = $class;
        $this->outline = $outline;
        $this->icon    = $icon;
        $this->fill    = $fill;

        $class  = new ReflectionClass(__CLASS__);
        $method = $class->getMethod('__construct');

        foreach ($method->getParameters() as $param) {
            if (in_array($param->name, ['icon', 'outline', 'class', 'fill'])) {
                continue;
            }

            if (${$param->name}) {
                $this->icon = Str::kebab($param->name);

                break;
            }
        }
    }

    public function render()
    {
        return view('cockpit::components.icons.' . $this->icon, [
            'outline' => $this->outline,
            'classes' => [
                'h-6 w-6'                       => !Str::contains($this->class, ['h-', 'w-']),
                'text-gray-500 dark:text-white' => !Str::contains($this->class, 'text-') && $this->fill,
                $this->class,
            ],
        ]);
    }
}
