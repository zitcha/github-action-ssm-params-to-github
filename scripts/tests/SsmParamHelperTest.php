<?php

namespace Tests;

use App\GitHubHelper;
use App\SsmParamHelper;
use App\SsmParamStoreToGitHubVars;
use PHPUnit\Framework\TestCase;

class SsmParamHelperTest extends TestCase
{
    use InvokePrivateMethodTrait;


    public function testGetSsmParamsByPathFlattened()
    {
//        $this->markTestSkipped();

        $helper = new SsmParamHelper();

        $actual = $this->invokePrivateMethod(
            $helper,
            'getSsmParamsByPathFlattened',
            [
                '/env-bt01/'
            ]
        );

        dump($actual);
    }
}