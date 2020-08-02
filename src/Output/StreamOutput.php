<?php

namespace thgs\Hex\Output;

use thgs\Hex\Stream\IOStream;

class StreamOutput implements DisplayOutputInterface
{
    private IOStream $stream;

    public function __construct(string $filename)
    {
        // not sure if we should just reuse that, but I just did.
        $this->stream = new IOStream($filename);
    }

    /**
     * @param string $string
     * @return void
     */
    public function outputLine(string $string): void
    {
        $handle = $this->stream->writeStream();
        fwrite($handle, $string . PHP_EOL);
        fclose($handle);
    }
}