<?php

namespace Ffcms\Console\Transfer;

class Input
{
    /**
     * Read console app command as array
     * @return string
     */
    public function read()
    {
        $handle = fopen('php://stdin', 'r');
        return trim(fgets($handle));
    }
}