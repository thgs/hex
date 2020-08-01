<?php

namespace thgs\Hex;

use thgs\Hex\Formatter\FormatterInterface;
use thgs\Hex\Formatter\SimpleHexFormatter;
use thgs\Hex\Output\DisplayOutputInterface;
use thgs\Hex\Output\EchoOutput;

class Editor
{
    private $filename;
    private FormatterInterface $formatter;
    private DisplayOutputInterface $display;

    /**
     * @param FormatterInterface $formatter
     * @param DisplayOutputInterface $display
     */
    public function __construct(FormatterInterface $formatter = null, DisplayOutputInterface $display = null)
    {
        $this->formatter = $formatter ?? new SimpleHexFormatter(8);
        $this->display = $display ?? new EchoOutput();
    }

    /**
     * @param string $file
     * @return void
     */
    public function selectFile($file)
    {
        if (!file_exists($file)) {
            throw new \Exception('File does not exist');
        }

        // maybe check r/w perms

        $this->filename = $file;
    }

    /**
     * @param integer $startingOffset
     * @param integer $readBytes
     * @return void
     */
    public function dump($startingOffset = 0, $readBytes = 512): void
    {
        $handle = fopen($this->filename, 'rb');

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
     * Undocumented function
     *
     * @param integer $startAt
     * @param string $value         without spaces for now
     * @return void
     */
    public function update($startAt, $value): void
    {
        // @todo store in history

        $handle = fopen($this->filename, 'rb+');
        fseek($handle, $startAt);
        $binary = hex2bin($value);

        fwrite($handle, $binary);   // is length important here or we can keep on writing?
        fclose($handle);
    }

    public function seek($startAt, $value, $limit = null): array
    {
        $handle = fopen($this->filename, 'rb');
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