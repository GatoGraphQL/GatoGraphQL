<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use WP_Post;

class CustomPostFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'isSticky',
            'modified',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'isSticky' => $this->translationAPI->__('Determines whether a custom post is sticky', 'customposts'),
            'modified' => $this->translationAPI->__('The custom post\'s local modified time', 'customposts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'isSticky' => SchemaDefinition::TYPE_BOOL,
            'modified' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'isSticky' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'modified':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'format',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                                $this->translationAPI->__('Date format, as defined in %s', 'customposts'),
                                'https://www.php.net/manual/en/function.date.php'
                            ),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->cmsService->getOption($this->nameResolver->getName('popcms:option:dateFormat')),
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'gmt',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Whether to retrieve the GMT time', 'customposts'),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => false,
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
        /** @var WP_Post */
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'isSticky':
                return \is_sticky($customPost->ID);
            case 'modified':
                $modifiedTime = \get_post_modified_time($fieldArgs['format'], $fieldArgs['gmt'], $customPost);
                if ($modifiedTime === false) {
                    return null;
                }
                return $modifiedTime;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
