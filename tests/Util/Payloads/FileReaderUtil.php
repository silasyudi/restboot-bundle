<?php

namespace SymfonyBoot\SymfonyBootBundle\Tests\Util\Payloads;

class FileReaderUtil
{

    public static function readFile(string $filename): string
    {
        $filename = dirname(__FILE__) . '/' . $filename;
        return trim(file_get_contents($filename));
    }
}
