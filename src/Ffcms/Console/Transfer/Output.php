<?php

namespace Ffcms\Console\Transfer;

class Output
{
    /**
     * Write console text with tab offset
     * @param string $text
     * @return string
     */
    public function writeTab($text)
    {
        return "\t->" . $this->write($text);
    }

    /**
     * @param $text
     * @return string
     */
    public function writeHeader($text)
    {
        return '=== ' . strip_tags((string)$text) . ' ===' . "\n";
    }

    /**
     * Safe write text to console
     * @param string $text
     * @return string
     */
    public function write($text)
    {
        return strip_tags((string)$text) . "\n";
    }
}