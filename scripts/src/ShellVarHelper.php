<?php

namespace App;

/**
 * Deal with Unix Shell variables (Environmental Variables)
 * These standards should also help generate a reliable dotenv file if needed
 * I suspect that writing to the file referred to by $GITHUB_OUTPUT is actually a
 * dotenv file which is use by Github Actions.
 */
class ShellVarHelper
{
    /**
     * This is a fairly strict validation
     * In reality shell variables names are much more flexible.
     */
    public function isVarNameValid(string $varName): bool
    {
        $match = preg_match(
            '~^[A-Z][0-9A-Z_]*$~',
            $varName
        );

        if ($match === 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * The strategy here, is to surround every value with double quotes,
     * This will allow spaces and hash symbol to work.
     * Existing souble quotes will be escaped with a slash
     */
    public function escapeVarValue(string $value): string
    {
        $value = str_replace('"', '\\"', $value);

        return '"'. $value . '"';
    }

    public function arrayToDotenvString(array $arr): string
    {
        $lines = [];
        foreach ($arr as $key => $value) {
            if (! $this->isVarNameValid($key) ) {
                throw new \Exception('"' . $key . '" is not a valid variable name');
            }

            $lines[] = $key . '=' . $this->escapeVarValue($value) . "\n";
        }

        return join('', $lines);
    }
}