<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

trait FixtureQueryExecutionGraphQLServerTestCaseTrait
{
    use FixtureTestCaseTrait;

    protected function getGraphQLResponseFile(string $filePath, string $fileName): string
    {
        $graphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.json';
        $fixtureFolder = $this->getFixtureFolder();
        $responseFixtureFolder = $this->getResponseFixtureFolder();
        if ($responseFixtureFolder !== $fixtureFolder) {
            $graphQLResponseFile = str_replace(
                $fixtureFolder,
                $responseFixtureFolder,
                $graphQLResponseFile
            );
        }
        return $graphQLResponseFile;
    }

    protected function getGraphQLVariablesFile(string $filePath, string $fileName): string
    {
        $graphQLVariablesFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.var.json';
        $fixtureFolder = $this->getFixtureFolder();
        $responseFixtureFolder = $this->getResponseFixtureFolder();
        if ($responseFixtureFolder !== $fixtureFolder) {
            $graphQLVariablesFile = str_replace(
                $fixtureFolder,
                $responseFixtureFolder,
                $graphQLVariablesFile
            );
        }
        return $graphQLVariablesFile;
    }
}
