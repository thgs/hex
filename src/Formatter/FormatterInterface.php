<?php

namespace thgs\Hex\Formatter;

interface FormatterInterface
{
    /**
     * @param string $input
     * @param integer $address
     * @return string
     */
    public function format(string $rawInput, int $address): string;

    /**
     * @return integer
     */
    public function splitLineAt(): int;
}