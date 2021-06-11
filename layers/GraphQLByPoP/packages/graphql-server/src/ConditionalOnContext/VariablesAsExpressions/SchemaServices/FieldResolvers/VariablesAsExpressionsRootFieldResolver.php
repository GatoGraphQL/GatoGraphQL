<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ConditionalOnContext\VariablesAsExpressions\SchemaServices\FieldResolvers;

use GraphQLByPoP\GraphQLQuery\Facades\GraphQLQueryConvertorFacade;
use GraphQLByPoP\GraphQLServer\ConditionalOnContext\MultipleQueryExecution\SchemaServices\DirectiveResolvers\ExportDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;

class VariablesAsExpressionsRootFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'exportedVariables',
            // 'exportedVariable',
            'echoVar',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'exportedVariables' => SchemaDefinition::TYPE_MIXED,
            // 'exportedVariable' => SchemaDefinition::TYPE_MIXED,
            'echoVar' => SchemaDefinition::TYPE_MIXED,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'exportedVariables' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        /** @var DirectiveResolverInterface */
        $exportDirectiveResolver = $this->instanceManager->getInstance(ExportDirectiveResolver::class);
        $exportDirectiveName = $exportDirectiveResolver->getDirectiveName();
        $descriptions = [
            'exportedVariables' => sprintf(
                $this->translationAPI->__('Returns a dictionary with the values for all variables exported through the `%s` directive', 'graphql-server'),
                $exportDirectiveName
            ),
            // 'exportedVariable' => sprintf(
            //     $this->translationAPI->__('Returns the value for a variable exported through the `%s` directive', 'graphql-server'),
            //     $exportDirectiveName
            // ),
            'echoVar' => $this->translationAPI->__('Returns the variable value', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            // case 'exportedVariable':
            //     return array_merge(
            //         $schemaFieldArgs,
            //         [
            //             [
            //                 SchemaDefinition::ARGNAME_NAME => 'name',
            //                 SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
            //                 SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
            //                     $this->translationAPI->__('Exported variable name. It must start with \'%s\'', 'graphql-server'),
            //                     QuerySymbols::VARIABLE_AS_EXPRESSION_NAME_PREFIX
            //                 ),
            //                 SchemaDefinition::ARGNAME_MANDATORY => true,
            //             ],
            //         ]
            //     );
            case 'echoVar':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'variable',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The variable to echo back, of any type', 'graphql-server'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
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
        $graphQLQueryConvertor = GraphQLQueryConvertorFacade::getInstance();
        switch ($fieldName) {
            case 'exportedVariables':
                // All the variables starting with "_" are treated as expressions
                return array_filter(
                    $variables ?? [],
                    function ($variableName) use ($graphQLQueryConvertor) {
                        return $graphQLQueryConvertor->treatVariableAsExpression($variableName);
                    },
                    ARRAY_FILTER_USE_KEY
                );
            // case 'exportedVariable':
            //     if ($variables) {
            //         return $variables[$fieldArgs['name']];
            //     }
            //     return null;
            case 'echoVar':
                return $fieldArgs['variable'];
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
