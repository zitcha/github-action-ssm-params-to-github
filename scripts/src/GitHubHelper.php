<?php

namespace App;

use Github\Client;
use Github\AuthMethod;

/**
 * @deprecated Not used any more.
 *
 * Requires "knplabs/github-api" to be added to composer.json
 */
class GitHubHelper
{
    protected Client $client;
    protected array $repositoryData;
    public function __construct(
        protected string $token, // e.g. '*********************iFgyjurcKhuMG1ximVT'
        protected string $organization, // e.g. 'the-pistol' or 'zitcha'
        protected string $repository // e.g. 'backend'
    ) {
        $this->client = $this->getClient();
        $this->repositoryData = $this->getRepositoryData();

    }
    protected function getClient(): Client
    {
        $client = new Client();
        $client->authenticate($this->token, authMethod: AuthMethod::ACCESS_TOKEN);

        return $client;
    }

    protected function getRepositoryData(): array
    {
        return $this->client->repository()->show($this->organization,$this->repository);
    }

    protected function getEnvironmentVariables(string $environment): array
    {
        // NOTE: this is only returning hte first page (Page size 10)

        return $this->client->deployments()->environments()->variables()->all($this->repositoryData['id'], $environment);
    }

    public function createEnvironment(
        string $githubOrganization,
        string $repositoryName,
        string $environmentName
    ): array
    {
        // NOTE: It is OK if the environment already exists.

        return $this->client->deployments()->environments()->createOrUpdate($githubOrganization, $repositoryName, $environmentName);
    }

    protected function createEnvironmentVariable(string $environment, $name, $value): array|string
    {
        // NOTE: there must be an existing environment to create the variable in, the environment will not be created automatically.
        print PHP_EOL . 'creating environment variable ' . $name;

        return $this->client->deployments()->environments()->variables()->create(
            $this->repositoryData['id'],
            $environment,
            [
                'name' => $name,
                'value' => $value
            ]
        );
    }

    protected function updateEnvironmentVariable(string $environment, $name, $value)
    {
        // NOTE: The variable must exist or this request will fail.

        $this->client->deployments()->environments()->variables()->update(
            $this->repositoryData['id'],
            $environment,
            $name,
            [
                'name' => $name,
                'value' => $value
            ]
        );
    }


    public function createEnvironmentVariables(string $environment, array $vars)
    {
        foreach ($vars as $name => $value) {
            $this->createEnvironmentVariable($environment, $name, $value);
        }
    }

    protected function deleteEnvironmentVariable(string $environment, string $variableName): array|string
    {
        return $this->client->deployments()->environments()->variables()->remove(
            $this->repositoryData['id'],
            $environment,
            $variableName
        );
    }

    public function deleteAllEnvironmentVariables(string $environment)
    {
        while(true) {
            // NOTE: getEnvironmentVariables is only getting the first page, hence the "while" loop
            $response = $this->getEnvironmentVariables($environment);
            $count = count($response['variables']);
            print PHP_EOL . 'Fetched page of environment variables with ' . $count . ' varaiables';

            if ($count === 0) {
                break;
            }

            foreach ($response['variables'] as $varData) {
                print PHP_EOL . 'deleting ' . $varData['name'];
                $this->deleteEnvironmentVariable($environment, $varData['name']);
            }
        }

    }
}