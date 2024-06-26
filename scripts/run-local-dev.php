<?php

require 'vendor/autoload.php';


$ssmParamHelper = new \App\LocalDevSsmParamHelper();

$ssmGh = new App\SsmParamStoreToGitHubVars($ssmParamHelper);

$vars = $ssmGh->getGitHubVarsFromSsmParams(
    \App\CliArgs::getEnvName()
);

$shellVarHelper = new \App\ShellVarHelper();
print $shellVarHelper->arrayToDotenvString($vars);



