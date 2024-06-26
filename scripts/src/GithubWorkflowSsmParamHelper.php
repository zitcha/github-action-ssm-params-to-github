<?php

namespace App;


use Aws\Ssm\SsmClient;

class GithubWorkflowSsmParamHelper extends AbstractSsmParamHelper
{
    protected function getSsmClient(): SsmClient
    {
        $args = [
            'version' => '2014-11-06',
        ];

        return new SsmClient($args);
    }

}