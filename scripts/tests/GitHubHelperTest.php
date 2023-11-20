<?php

namespace Tests;

use App\GitHubHelper;
use PHPUnit\Framework\TestCase;

/**
 * @group skip
 *
 * The GitHub helper is not used at the moment.
 */
class GitHubHelperTest extends TestCase
{
    use InvokePrivateMethodTrait;

    protected function getFirstGitHubRepository(): string
    {
        $repos = explode(',', $_ENV['GITHUB_REPOSITORY']);

        return $repos[0];
    }

    protected function getGitHubHelper(): GitHubHelper
    {

        return new GitHubHelper(
            $_ENV['GITHUB_API_TOKEN'],
            $_ENV['GITHUB_ORGANIZATION'],
            $this->getFirstGitHubRepository(),
        );
    }

    public function testGetEnvironmentVariables()
    {
//        $this->markTestSkipped();

        $helper = $this->getGitHubHelper();

        $actual = $this->invokePrivateMethod(
            $helper,
            'getEnvironmentVariables',
            [
                $_ENV['ENV_NAME_SELECTOR'],
            ]
        );

        dd($actual);
    }

    public function testCreateEnvironment()
    {
        $helper = $this->getGitHubHelper();

        $this->invokePrivateMethod(
            $helper,
            'createEnvironment',
            [
                $_ENV['GITHUB_ORGANIZATION'],
                $this->getFirstGitHubRepository(),
                'my-new-env-name',
            ]
        );
    }

    public function testCreateEnvironmentVariable()
    {
        $helper = $this->getGitHubHelper();

        $this->invokePrivateMethod(
            $helper,
            'createEnvironmentVariable',
            [
                $_ENV['ENV_NAME_SELECTOR'],
                'MY_KEY_1_SINGLE',
                'my value 1 single'
            ]
        );
    }

    public function testUpdateEnvironmentVariable()
    {
        $helper = $this->getGitHubHelper();

        $this->invokePrivateMethod(
            $helper,
            'updateEnvironmentVariable',
            [
                $_ENV['ENV_NAME_SELECTOR'],
                'MY_KEY_1_SINGLE',
                'my value 1 single - updated'
            ]
        );
    }

    public function testCreateEnvironmentVariables()
    {
        $this->markTestSkipped();

        $helper = $this->getGitHubHelper();

        $this->invokePrivateMethod(
            $helper,
            'createEnvironmentVariables',
            [
                $_ENV['ENV_NAME_SELECTOR'],
                [
                    'MY_KEY_1' => 'my value 1',
                    'MY_KEY_2' => 'my value 2',
                ]
            ]
        );
    }

    public function testDeleteEnvironmentVariable()
    {
        $this->markTestSkipped();

        $helper = $this->getGitHubHelper();

        $this->invokePrivateMethod(
            $helper,
            'deleteEnvironmentVariable',
            [
                $_ENV['ENV_NAME_SELECTOR'],
                'MY_KEY_1'
            ]
        );
    }

    public function testDeleteAllEnvironmentVariables()
    {
        $this->markTestSkipped();

        $helper = $this->getGitHubHelper();

        $this->invokePrivateMethod(
            $helper,
            'deleteAllEnvironmentVariables',
            [
                $_ENV['ENV_NAME_SELECTOR'],
            ]
        );
    }

}