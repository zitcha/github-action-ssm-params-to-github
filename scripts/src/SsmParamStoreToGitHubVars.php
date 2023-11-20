<?php

namespace App;

use Aws\Credentials\CredentialProvider;

class SsmParamStoreToGitHubVars
{
    protected SsmParamHelper $ssmParamHelper;

    public function __construct(
        SsmParamHelper $ssmParamHelper
    ) {
        $this->ssmParamHelper = $ssmParamHelper;
    }

    public function getGitHubVarsFromSsmParams(
        string $envName,
    ): array
    {
        $gitHubVars = [];

        // "Env Level" params
        $keyedParams = $this->getKeyedParamsByGroup(InfraLevel::Env, $envName);

        $fndName = $keyedParams['/fnd-name'];
        foreach ($keyedParams as $key => $value) {
            $key = $this->ssmParamNameToGitHubVarName('E_' . $key);
            $gitHubVars[$key] = $value;
        }

        // "Fnd Level" params
        $keyedParams = $this->getKeyedParamsByGroup(InfraLevel::Fnd, $fndName);
        $orgName = $keyedParams['/org-name'];
        foreach ($keyedParams as $key => $value) {
            $key = $this->ssmParamNameToGitHubVarName('F_' . $key);
            $gitHubVars[$key] = $value;
        }

        // "Org Level" params
        $keyedParams = $this->getKeyedParamsByGroup(InfraLevel::Org, $orgName);
        $uniName = 'uni';
        foreach ($keyedParams as $key => $value) {
            $key = $this->ssmParamNameToGitHubVarName('O_' . $key);
            $gitHubVars[$key] = $value;
        }

        // "Uni Level" params
        $keyedParams = $this->getKeyedParamsByGroup(InfraLevel::Uni, $uniName);
        foreach ($keyedParams as $key => $value) {
            $key = $this->ssmParamNameToGitHubVarName('U_' . $key);
            $gitHubVars[$key] = $value;
        }

        return $gitHubVars;
    }


    /**
     *
     * @param string $group e.g. bt01, beta, zitcha, crucial
     * @return array
     */
    protected function getKeyedParamsByGroup(InfraLevel $infraLevel, string $group): array
    {
        $keyedParams = [];

        $path = '/' . $infraLevel->value . '-' .  $group;

        $flattenedParams = $this->ssmParamHelper->getSsmParamsByPathFlattened($path);

        foreach ($flattenedParams as $key => $value) {
            $key = $this->stripPathFromSsmParamName($path, $key);
            $keyedParams[$key] = $value;
        }

        if (empty($keyedParams)) {
            throw new \Exception('getKeyedParamsByGroup seems to have an empty array');
        }

        return $keyedParams;
    }


    protected function stripPathFromSsmParamName(string $path, string $paramName): string
    {
        $count = null;
        $paramName = preg_replace('~^' . $path . '~', '', $paramName, count: $count);

        if ($count != 1) {
            throw new \Exception('it seems stripPathFromSsmParamName() failed');
        }

        return $paramName;
    }

    /**
     * https://docs.github.com/en/actions/learn-github-actions/variables#naming-conventions-for-environment-variables
     */
    protected function ssmParamNameToGitHubVarName(string $ssmParamName): string
    {
        $ssmParamName = preg_replace('~[^a-zA-Z0-9]+~', '_', $ssmParamName);
        $ssmParamName = trim($ssmParamName, '_');
        return strtoupper($ssmParamName);
    }
}