<?php

namespace thgs\Hex\Output;

interface DisplayOutputInterface
{
    /**
     * @param string $string
     * @return void
     */
    public function outputLine(string $string): void;
}