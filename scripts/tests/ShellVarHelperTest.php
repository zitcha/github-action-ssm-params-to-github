<?php

namespace Tests;

use App\ShellVarHelper;
use PHPUnit\Framework\TestCase;

/**
 * @group testnow
 */
class ShellVarHelperTest extends TestCase
{
    private function getTempFilePath(): string
    {
        return tempnam(sys_get_temp_dir(), 'phpunit');
    }

    public function testIsVarNameValid()
    {
        $helper = new ShellVarHelper();

        $this->assertTrue( $helper->isVarNameValid('MYVAR100') );
        $this->assertTrue( $helper->isVarNameValid('MY_VAR') );
        $this->assertFalse( $helper->isVarNameValid('invalid') ); // Low case chars
        $this->assertFalse( $helper->isVarNameValid('NO SPACES') ); // spaces not allowed
        $this->assertFalse( $helper->isVarNameValid('0START') ); // Must start with an alpha char
    }

    public function testEscapeVarValue()
    {
        $helper = new ShellVarHelper();

        $this->assertEquals(
            '"abc\\"123"',
            $helper->escapeVarValue('abc"123')
        );
    }

    public function testAppendArrayToDotenvFile()
    {
        $this->doesNotPerformAssertions();

        $helper = new ShellVarHelper();

        $tempFilePath = $this->getTempFilePath();

        $arr = [
            'VAR1' => 'Value 1',
            'VAR_WITH_QUOTE' => 'My Value with "'
        ];

        $helper->appendArrayToDotenvFile($arr, $tempFilePath);
    }
}