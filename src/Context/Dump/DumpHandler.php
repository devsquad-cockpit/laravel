<?php

namespace Cockpit\Context\Dump;

use Cockpit\Context\DumpContext;
use Symfony\Component\VarDumper\Cloner\VarCloner;

class DumpHandler
{
    protected DumpContext $dump;

    public function __construct(DumpContext $dump)
    {
        $this->dump = $dump;
    }

    public function dump($value): void
    {
        $this->dump->record(
            (new VarCloner())->cloneVar($value)
        );
    }
}
