<?php

namespace App;


use Aws\Credentials\CredentialProvider;
use Aws\Ssm\SsmClient;

class LocalDevSsmParamHelper extends AbstractSsmParamHelper
{
    protected function getSsmClient(): SsmClient
    {
        $awsProfile = getenv('AWS_PROFILE');

        if (empty($awsProfile)) {
            throw new \Exception("AWS_PROFILE env var is not set");
        }

        Stderr::write('AWS Profile: ' . $awsProfile);

        // You also need to log into AWS CLI SSO on the host with `aws sso login --profile MY_PROFILE`
        $provider = CredentialProvider::sso('zitcha');

        $args = [
            'version' => '2014-11-06',
            'credentials' => $provider,
//            'profile' => 'zitcha',
            'region' => 'ap-southeast-2'
//            'profile' => $awsProfile
        ];

        return new SsmClient($args);
    }

}