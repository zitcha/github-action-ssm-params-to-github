<?php

namespace Tests;

use App\AbstractSsmParamHelper;
use App\SsmParamStoreToGitHubVars;
use App\InfraLevel;
use PHPUnit\Framework\TestCase;

class SsmParamStoreToGitHubVarsTest extends TestCase
{
    use InvokePrivateMethodTrait;

    protected function getSsmParamStoreToGitHubVarsService(): SsmParamStoreToGitHubVars
    {
        return new SsmParamStoreToGitHubVars(
            new AbstractSsmParamHelper()
        );
    }

    /**
     * @group testnow2
     */
    public function testGetGitHubVarsFromSsmParams()
    {
        $gitHubVars = $this->getSsmParamStoreToGitHubVarsService()->getGitHubVarsFromSsmParams(
            'bt01',
        );

        dump($gitHubVars);
    }

    public function testStripPathFromSsmParamName()
    {
        $this->markTestSkipped();

        $actual = $this->invokePrivateMethod(
            $this->getSsmParamStoreToGitHubVarsService(),
            'stripPathFromSsmParamName',
            [
                '/env-bt20',
                '/env-bt20/my/param-/user-name/'
            ]
        );

        $this->assertEquals('/my/param-/user-name/', $actual);
    }

    /**
     * @group testnow2
     */
    public function testGetKeyedParamsByGroup()
    {
        $keyedParams = $this->invokePrivateMethod(
            $this->getSsmParamStoreToGitHubVarsService(),
            'getKeyedParamsByGroup',
            [
                InfraLevel::Env,
                'bt01'
            ]
        );


        dump('SAM', $keyedParams);
    }
    public function testStripPathFromSsmParamNameFail()
    {
        $this->markTestSkipped();

        $this->expectException(\Exception::class);

        $actual = $this->invokePrivateMethod(
            $this->getSsmParamStoreToGitHubVarsService(),
            'stripPathFromSsmParamName',
            [
                '/env-bt20',
                '/bit-missing/my/param-/user-name/'
            ]
        );

        $this->assertEquals('/my/param-/user-name/', $actual);
    }

    public function testSsmParamNameToGithubVarName()
    {
        $this->markTestSkipped();

        $actual = $this->invokePrivateMethod(
            $this->getSsmParamStoreToGitHubVarsService(),
            'ssmParamNameToGitHubVarName',
            [
                '/my/param-/user-name/'
            ]
        );

        $this->assertEquals('MY_PARAM_USER_NAME', $actual);
    }

}