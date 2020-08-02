<?php

namespace thgs\Hex\Stream;

use thgs\Hex\Exception\FileNotFound;

class IOStream implements StreamProviderInterface
{
    private string $filename;

    /**
     * @param string|\SplFileInfo $filename
     * @throws FileNotFound
     */
    public function __construct($filename)
    {
        if ($this->hasWrapper($filename)) {
            $this->filename = $filename;
            return;
        }

        if (is_string($filename) && !file_exists($filename)) {
            throw new FileNotFound($filename);
        }

        // maybe check r/w perms

        $this->filename = $filename;
    }

    /**
     * @return resource
     */
    public function readStream()
    {
        return fopen($this->filename, 'rb');
    }

    /**
     * @return resource
     */
    public function writeStream()
    {
        return fopen($this->filename, 'rb+');
    }

    /**
     * @param string $filename
     * @return boolean
     */
    private function hasWrapper(string $filename): bool
    {
        return false !== strpos($filename, '//');
    }
}