<?php

namespace thgs\Hex\Exception;

class FileNotFound extends Hexception
{
    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        parent::__construct(static::FILE_NOT_FOUND . '(' . $filename .')');
    }
}