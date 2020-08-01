<?php

namespace thgs\Hex\Stream;

use thgs\Hex\Exception\UnableToCopyFile;
use TypeError;

class OperateOnCopy extends IOStream
{
    private string $outputFilename;

    /**
     * @param string $inputFilename     string for now
     * @param string $outputFilename    string for now
     */
    public function __construct(string $inputFilename, string $outputFilename)
    {
        parent::__construct($inputFilename);

        $this->outputFilename = $outputFilename;
    }

    /**
     * @inheritDoc
     */
    public function writeStream()
    {
        // @todo bit unsure about this if
        if (!file_exists($this->outputFilename)) {
            $this->makeCopy();
        }

        return fopen($this->outputFilename, 'rb+');
    }

    /**
     * @param resource|null $context
     * @return void
     * @throws UnableToCopyFile
     * @throws TypeError            when $context is not a resource|null
     */
    protected function makeCopy($context = null): void
    {
        if ($context && !is_resource($context)) {
            throw new TypeError('Not a resource passed to context');
        }

        // @todo more checks??
        if (!copy($this->filename, $this->outputFilename, $context) || !file_exists($this->outputFilename)) {
            throw new UnableToCopyFile($this->outputFilename);
        }
    }
}