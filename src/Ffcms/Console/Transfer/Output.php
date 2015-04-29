<?php

namespace Ffcms\Console\Transfer;

class Output
{
    /**
     * Write console header for action
     * @param string $text
     * @return string
     */
    public function writeHeader($text)
    {
        return '     ->' . $this->write($text);
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