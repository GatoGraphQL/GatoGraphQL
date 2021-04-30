<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\FieldResolvers\Legacy\Version_0_1_0;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;

class RootFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getPriorityToAttachToClasses(): int
    {
        // Higher priority => Process before the latest version fieldResolver
        return 20;
    }

    public function getSchemaFieldVersion(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return '0.1.0';
    }

    public function decideCanProcessBasedOnVersionConstraint(TypeResolverInterface $typeResolver): bool
    {
        return true;
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'userServiceURLs',
            'userServiceData',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'userServiceURLs' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_URL),
            'userServiceData' => SchemaDefinition::TYPE_OBJECT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'userServiceURLs':
            case 'userServiceData':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'githubRepo',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('GitHub repository for which to fetch data, in format \'account/repo\' (eg: \'leoloso/PoP\')', 'examples-for-pop'),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => 'leoloso/PoP',
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'userServiceURLs' => $this->translationAPI->__('Services used in the application: GitHub data for a specific repository', 'examples-for-pop'),
            'userServiceData' => $this->translationAPI->__('Retrieve data from the services', 'examples-for-pop'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'userServiceURLs':
                return [
                    'github' => sprintf(
                        'https://api.github.com/repos/%s',
                        $fieldArgs['githubRepo']
                    ),
                ];
            case 'userServiceData':
                $userServiceURLs = $typeResolver->resolveValue(
                    $resultItem,
                    $this->fieldQueryInterpreter->getField(
                        'userServiceURLs',
                        $fieldArgs
                    ),
                    $variables,
                    $expressions,
                    $options
                );
                if (GeneralUtils::isError($userServiceURLs)) {
                    return $userServiceURLs;
                }
                $userServiceURLs = (array)$userServiceURLs;
                return $typeResolver->resolveValue(
                    $resultItem,
                    $this->fieldQueryInterpreter->getField(
                        'getAsyncJSON',
                        [
                            'urls' => $userServiceURLs,
                        ]
                    ),
                    $variables,
                    $expressions,
                    $options
                );
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
