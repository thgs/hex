<?php

namespace thgs\Hex\Output;

class EchoOutput implements DisplayOutputInterface
{
    /**
     * @param string $string
     * @return void
     */
    public function outputLine($string): void
    {
        echo $string . PHP_EOL;
    }
}