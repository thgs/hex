<?php

namespace thgs\Hex;

class Editor
{
    private $filename;

    public function selectFile($file)
    {
        if (!file_exists($file)) {
            throw new \Exception('File does not exist');
        }

        // maybe check r/w perms

        $this->filename = $file;
    }

    /**
     * @param integer $offset
     * @param integer $endOffset
     * @param integer $lineAt       LineAt could be also a formatter option?
     * @return void
     */
    public function dump($offset = 0, $endOffset = 512, $lineAt = 32): void
    {
        $handle = fopen($this->filename, 'rb');
        $data = fread($handle, $endOffset); // read everything, assume offset starts=0
        fclose($handle);

        $lines = str_split($data, $lineAt);

        foreach ($lines as $kOffset => $lineData) {
            $hex = wordwrap(bin2hex($lineData), 2, ' ', true);

            echo str_pad(dechex($kOffset * $lineAt), 4, '0', STR_PAD_LEFT) . ': ';  // address
            echo str_pad($hex, $lineAt*2 + $lineAt, ' ');                           // hex
            echo $this->prettyPrint($lineData) . PHP_EOL;                                  // output in Ascii
        }
    }

    public function prettyPrint($string): void
    {
        $output = '';
        $split = str_split($string, 1);
        foreach ($split as $byte) {
            $ord = ord($byte);
            $output .= $ord >= 32 && $ord <= 127        // not sure about this range
                ? $byte
                : '.';
        }
        echo $output;
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