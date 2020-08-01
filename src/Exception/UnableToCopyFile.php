<?php

namespace thgs\Hex\Exception;

class UnableToCopyFile extends Hexception
{
    public function __construct($filename)
    {
        parent::__construct(static::UNABLE_TO_COPY . ': ' . $filename);
    }
}