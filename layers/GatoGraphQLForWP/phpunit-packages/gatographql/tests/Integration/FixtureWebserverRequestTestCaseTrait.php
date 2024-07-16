<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use RuntimeException;
use stdClass;

use function file_exists;
use function file_get_contents;
use function json_decode;

trait FixtureWebserverRequestTestCaseTrait
{
    /**
     * @return array<string,mixed>
     */
    protected static function getGraphQLVariables(string $graphQLVariablesFile): array
    {
        if (!file_exists($graphQLVariablesFile)) {
            return [];
        }

        $fileContents = file_get_contents($graphQLVariablesFile);
        if ($fileContents === false) {
            throw new RuntimeException(
                sprintf(
                    'File "%s" cannot be read',
                    $graphQLVariablesFile
                )
            );
        }
        $variables = json_decode($fileContents);
        if (!is_array($variables) && !($variables instanceof stdClass)) {
            throw new RuntimeException(
                sprintf(
                    'Decoding the JSON inside file "%s" failed',
                    $graphQLVariablesFile
                )
            );
        }
        return (array) $variables;
    }
}
