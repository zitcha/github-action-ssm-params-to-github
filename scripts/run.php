<?php

require 'vendor/autoload.php';

//if ($argc !== 2) {
//    throw new \Exception('You must pass exactly 1 argument, typically the value of GITHUB_OUTPUT from the Github Action');
//}
//
//define('GITHUB_OUTPUT', $argv[1]);

$envName = 'experiment-01'; // TODO-SAM param hard coded.

$ssmGh = new App\SsmParamStoreToGitHubVars(new \App\SsmParamHelper());

$vars = $ssmGh->getGitHubVarsFromSsmParams(
    $envName,
);

//print (count($vars) . ' SSM Params found');
//print ('GITHUB_OUTPUT: ' . GITHUB_OUTPUT);
$shellVarHelper = new \App\ShellVarHelper();
print $shellVarHelper->arrayToDotenvString($vars);



