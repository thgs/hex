<?php

namespace thgs\Hex\Formatter;

class SimpleHexFormatter implements FormatterInterface
{
    private const SEPARATE_HEX_AT = 2;      // 1 byte

    /**
     * @param int $splitLineAt
     * @param int $sectionPadding
     * @param int $asciiPadding
     * @param string $controlCharacter
     *
     * @todo add option for extra padding, as in every 4/8/16/32 bytes, etc
     */
    public function __construct(int $splitLineAt = 32, int $sectionPadding = 1, int $asciiPadding = 1, string $controlCharacter = '.')
    {
        $this->options = [
            'splitLineAt' => $splitLineAt,
            'sectionPadding' => $sectionPadding,
            'padding' => $asciiPadding,
            'controlCharacterSub' => $controlCharacter,

            // private (precalculated)
            '_maxHexLength' => $this->calculateMaxLineAt($splitLineAt)
        ];
    }

    /**
     * @param int $splitLineAt
     * @return int
     */
    private function calculateMaxLineAt(int $splitLineAt): int
    {
        return $splitLineAt * self::SEPARATE_HEX_AT + $splitLineAt;
    }

    /**
     * @param string $rawInput
     * @param int $address          Might have to change in the future
     * @return string
     */
    public function format(string $rawInput, int $address): string
    {
        $splitHex = wordwrap(bin2hex($rawInput), self::SEPARATE_HEX_AT, ' ', true);

        return
            str_pad(dechex($address * $this->options['splitLineAt']), 4, '0', STR_PAD_LEFT) . ': '      // address
            . str_pad($splitHex, $this->options['_maxHexLength'], ' ')                                  // hex
            . ' |' . str_repeat(' ', $this->options['sectionPadding'])                                  // separator
            . $this->asciiPrettyPrint($rawInput)                                                        // ascii
            ;
    }

    /**
     * @todo support here more than ascii?
     *
     * @param string $string
     * @return void
     */
    private function asciiPrettyPrint($string): string
    {
        $output = '';
        foreach (str_split($string, 1) as $byte) {
            $ord = ord($byte);
            $output .= $ord >= 32 && $ord <= 127        // not sure about this range
                ? $byte
                : $this->options['controlCharacterSub'];
            $output .= str_repeat(' ', $this->options['padding']);
        }
        return $output;
    }

    /**
     * @return int
     */
    public function splitLineAt(): int
    {
        return $this->options['splitLineAt'];
    }
}