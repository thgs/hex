<?php

namespace thgs\Hex\Exception;

class StreamNotInitialised extends Hexception
{
    public static function assertNotEmpty($value): void
    {
        if (!$value) {
            throw new static('IO Stream not initialised.');
        }
    }
}