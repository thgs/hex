<?php

namespace thgs\Hex\Stream;

interface StreamProviderInterface
{
    /**
     * @return resource
     */
    public function readStream();

    /**
     * @return resource
     */
    public function writeStream();

    // @todo maybe the implementations also implement all stream methods? or __call()
}