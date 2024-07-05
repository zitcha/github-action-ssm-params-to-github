<?php

namespace App;


class Stderr
{
    public static function write($string)
    {
        file_put_contents( "php://stderr", $string . PHP_EOL);
    }
}