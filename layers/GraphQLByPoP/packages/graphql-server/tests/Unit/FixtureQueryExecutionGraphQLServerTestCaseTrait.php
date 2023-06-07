<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

trait FixtureQueryExecutionGraphQLServerTestCaseTrait
{
    use FixtureTestCaseTrait;

    protected static function getGraphQLResponseFile(string $filePath, string $fileName): string
    {
        $graphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.json';
        $fixtureFolder = static::getFixtureFolder();
        $responseFixtureFolder = static::getResponseFixtureFolder();
        if ($responseFixtureFolder !== $fixtureFolder) {
            $graphQLResponseFile = str_replace(
                $fixtureFolder,
                $responseFixtureFolder,
                $graphQLResponseFile
            );
        }
        return $graphQLResponseFile;
    }

    protected static function getGraphQLVariablesFile(string $filePath, string $fileName): string
    {
        $graphQLVariablesFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.var.json';
        $fixtureFolder = static::getFixtureFolder();
        $responseFixtureFolder = static::getResponseFixtureFolder();
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
