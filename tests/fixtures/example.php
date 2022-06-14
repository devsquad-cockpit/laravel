<?php

class Example
{
    protected $qty;

    public function __construct(int $qty)
    {
        $this->qty = $qty;
    }

    public function execute()
    {
        if ($this->qty < 1) {
            throw new InvalidArgumentException('The quantity must be greater than 1');
        }
    }
}
