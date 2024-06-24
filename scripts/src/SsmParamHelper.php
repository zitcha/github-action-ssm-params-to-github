<?php

namespace App;

use Aws\Credentials\CredentialProvider;
use Aws\Ssm\SsmClient;

class SsmParamHelper
{
    private $ssmClient;

    public function __construct() {
        $this->ssmClient = $this->getSsmClient();
    }

    protected function getSsmClient(): SsmClient
    {
        $args = [
            'version' => '2014-11-06',
        ];

        return new SsmClient($args);
    }

    /**
     * @internal Currently not used, but might be useful on occasion for testing from local dev
     */
    protected function getSsmClientForLocalDev(): SsmClient
    {
        // Use AWS SSO Profile called "zitcha". This is still configured in $HOME/.aws but is slightly different ot a standard profile
        // You also need to log into AWS CLI SSO on the host with `aws sso login --profile zitcha`
        $provider = CredentialProvider::sso('zitcha');

        $args = [
            'version' => '2014-11-06',
            'credentials' => $provider
        ];

        if (!empty($_ENV['AWS_PROFILE'])) {
            $args['profile'] = $_ENV['AWS_PROFILE'];
        }

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