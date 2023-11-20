<?php

namespace App;

use Aws\Credentials\CredentialProvider;
use Aws\Ssm\SsmClient;

class SsmParamHelper
{
    private $ssmClient;

    public function __construct() {
        $this->ssmClient = $this->getSsmClient(); // TODO-SAM
    }

    protected function getSsmClient(): SsmClient
    {
        // TODO-SAM tidy up/remove any unneeded "provider" related code/comments
        // Use AWS SSO Profile called "zitcha". This is still configured in $HOME/.aws but is slightly different ot a standard profile
        // You also need to log into AWS CLI SSO on the host with `aws sso login --profile zitcha`
        // $provider = CredentialProvider::sso('zitcha');

        $args = [
            'region' => 'ap-southeast-2', // SDK didn't seem to get the region from the environmental var automatically
            'version' => '2014-11-06',
//            'credentials' => $provider
        ];

//        if (!empty($_ENV['AWS_PROFILE'])) {
//            $args['profile'] = $_ENV['AWS_PROFILE'];
//        }

        // TODO-SAM hacked profile
//        $args['profile'] = 'zitcha-iam-user';

        return new SsmClient($args);
    }


    /**
     * @param string $path
     */
    public function getSsmParamsByPathFlattened(string $path): array
    {
        $flattenedParams = [];

        $input = [
            'Recursive' => true,
            'MaxResults' => 10,
            'Path' => $path
        ];

        $results = $this->ssmClient->getPaginator('GetParametersByPath', $input);

        foreach ($results as $result) {
            foreach ($result['Parameters'] as $param) {
                $key = $param['Name'];
                $value = $param['Value'];
                $flattenedParams[$key] = $value;
            }
        }

        return $flattenedParams;
    }

}