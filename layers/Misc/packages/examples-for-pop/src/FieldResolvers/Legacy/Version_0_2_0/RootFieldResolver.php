<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\FieldResolvers\Legacy\Version_0_2_0;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class RootFieldResolver extends \Leoloso\ExamplesForPoP\FieldResolvers\Legacy\Version_0_1_0\RootFieldResolver
{
    public function getSchemaFieldVersion(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return '0.2.0';
    }
    public function getPriorityToAttachClasses(): ?int
    {
        return 30;
    }

    public function decideCanProcessBasedOnVersionConstraint(TypeResolverInterface $typeResolver): bool
    {
        return true;
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'userServiceURLs':
            case 'userServiceData':
                // Find the "githubRepo" parameter, and change its default value
                return array_map(
                    function ($arg) {
                        if ($arg[SchemaDefinition::ARGNAME_NAME] == 'githubRepo') {
                            $arg[SchemaDefinition::ARGNAME_DEFAULT_VALUE] = 'getpop/component-model';
                        }
                        return $arg;
                    },
                    $schemaFieldArgs
                );
        }

        return $schemaFieldArgs;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $value = parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
        switch ($fieldName) {
            case 'userServiceURLs':
                // Override the default value
                return array_merge(
                    $value,
                    [
                        'github' => sprintf(
                            'https://api.github.com/repos/%s',
                            $fieldArgs['githubRepo']
                        ),
                    ]
                );
        }

        return $value;
    }
}
