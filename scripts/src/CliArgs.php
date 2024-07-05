<?php

namespace App;


class CliArgs
{
    public static function getEnvName(): string
    {
        global $argc;
        global $argv;

        if ($argc !== 2) {
            throw new \Exception('You must pass exactly 1 argument which should be the "env name"');
        }

        $envName = $argv[1];
        if (strlen($envName) < 3) {
            throw new \Exception('"env name" string length seems too short');
        }

        Stderr::write('envName determined as "' . $envName . '"');

        return $envName;
    }
}