<?php

require 'vendor/autoload.php';

if ($argc !== 2) {
    throw new \Exception('You must pass exactly 1 argument which should be the "env name"');
}

$envName = $argv[1];
if (strlen($envName) < 3) {
    throw new \Exception('"env name" string length seems too short');
}

$ssmGh = new App\SsmParamStoreToGitHubVars(new \App\SsmParamHelper());

$vars = $ssmGh->getGitHubVarsFromSsmParams(
    $envName,
);

//print (count($vars) . ' SSM Params found');
//print ('GITHUB_OUTPUT: ' . GITHUB_OUTPUT);
$shellVarHelper = new \App\ShellVarHelper();
print $shellVarHelper->arrayToDotenvString($vars);



