<?php

namespace thgs\Hex;

use thgs\Hex\Exception\StreamNotInitialised;
use thgs\Hex\Formatter\FormatterInterface;
use thgs\Hex\Formatter\SimpleHexFormatter;
use thgs\Hex\Output\DisplayOutputInterface;
use thgs\Hex\Output\EchoOutput;
use thgs\Hex\Stream\IOStream;
use thgs\Hex\Stream\OperateOnCopy;
use thgs\Hex\Stream\StreamProviderInterface;

class Editor
{
    /**
     * @var StreamProviderInterface|null
     */
    private $stream;
    private FormatterInterface $formatter;
    private DisplayOutputInterface $display;

    /**
     * @param FormatterInterface $formatter
     * @param DisplayOutputInterface $display
     * @param StreamProviderInterface $stream
     */
    public function __construct(
        FormatterInterface $formatter = null,
        DisplayOutputInterface $display = null,
        StreamProviderInterface $stream = null
    ) {
        $this->formatter = $formatter ?? new SimpleHexFormatter(8);
        $this->display = $display ?? new EchoOutput();
        $this->stream = $stream;
    }

    /**
     * @param string $file
     * @return void
     */
    public function selectFile($file): void
    {
        $this->stream = new IOStream($file);
    }

    /**
     * @param string $inputFile     string for now
     * @param string $outputFile    string for now
     * @return void
     */
    public function operateOnCopy($inputFile, $outputFile): void
    {
        $this->stream = new OperateOnCopy($inputFile, $outputFile);
    }

    /**
     * @param integer $startingOffset
     * @param integer $readBytes
     * @return void
     *
     * @throws StreamNotInitialised
     */
    public function dump($startingOffset = 0, $readBytes = 512): void
    {
        StreamNotInitialised::assertNotEmpty($this->stream);

        $handle = $this->stream->readStream();

        if ($startingOffset) {
            fseek($handle, $startingOffset);
        }
        $data = fread($handle, $readBytes);
        fclose($handle);

        // @todo this needs a better implementation (memory)
        $lines = str_split($data, $this->formatter->splitLineAt());

        foreach ($lines as $currentOffset => $lineData) {
            $formatted = $this->formatter->format($lineData, $startingOffset + $currentOffset, );
            $this->display->outputLine($formatted);
        }
    }

    /**
     * @param integer $startAt
     * @param string $value         without spaces for now
     * @return void
     *
     * @throws StreamNotInitialised
     */
    public function update($startAt, $value): void
    {
        StreamNotInitialised::assertNotEmpty($this->stream);

        // @todo store in history
        $handle = $this->stream->writeStream();
        fseek($handle, $startAt);
        $binary = hex2bin($value);

        fwrite($handle, $binary);   // is length important here or we can keep on writing?
        fclose($handle);
    }

    /**
     * @param int $startAt
     * @param string $value         string for now
     * @param int|null $limit
     * @return array                array for now
     */
    public function seek(int $startAt, string $value, ?int $limit = null): array
    {
        StreamNotInitialised::assertNotEmpty($this->stream);

        $handle = $this->stream->readStream();
        fseek($handle, $startAt);
        $binary = hex2bin($value);

        // @todo implement limit too

        $addStartingOffset = fn($x) => $x + $startAt;       // whats wrong here?

        $results = [];
        while ($data = fread($handle, 8192)) {
            $results[] = array_map($addStartingOffset, $this->strpos_recursive($data, $binary));
        }

        // flatten results?

        return $results;
    }

    private function strpos_recursive($haystack, $needle)
    {
        $occurrencies = [];
        $currentOffset = 0;
        $needleLength = strlen($needle);

        while (false !== ($found = strpos($haystack, $needle, $currentOffset))) {
            $occurrencies[] = $found;
            $currentOffset = $found + $needleLength;
        }

        return $occurrencies;
    }
}