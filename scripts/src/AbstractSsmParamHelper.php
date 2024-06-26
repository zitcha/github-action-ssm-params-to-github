<?php

namespace App;

use Aws\Credentials\CredentialProvider;
use Aws\Ssm\SsmClient;

abstract class AbstractSsmParamHelper
{
    private $ssmClient;

    public function __construct() {
        $this->ssmClient = $this->getSsmClient();

        Stderr::write('Final Ssm Client region: ' . $this->ssmClient->getRegion() );
    }

    abstract protected function getSsmClient(): SsmClient;

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

        Stderr::write('Requesting SSM Param Path ' . $path);
        $results = $this->ssmClient->getPaginator('GetParametersByPath', $input);

        foreach ($results as $result) {
            Stderr::write('Requesting SSM Param page');
            foreach ($result['Parameters'] as $param) {
                $key = $param['Name'];
                $value = $param['Value'];
                $flattenedParams[$key] = $value;
            }
        }

        return $flattenedParams;
    }

}