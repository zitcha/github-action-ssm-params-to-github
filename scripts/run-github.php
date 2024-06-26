<?php

require 'vendor/autoload.php';


$ssmParamHelper = new \App\GithubWorkflowSsmParamHelper();

$ssmGh = new App\SsmParamStoreToGitHubVars($ssmParamHelper);

$vars = $ssmGh->getGitHubVarsFromSsmParams(
    \App\CliArgs::getEnvName()
);

$shellVarHelper = new \App\ShellVarHelper();
print $shellVarHelper->arrayToDotenvString($vars);



