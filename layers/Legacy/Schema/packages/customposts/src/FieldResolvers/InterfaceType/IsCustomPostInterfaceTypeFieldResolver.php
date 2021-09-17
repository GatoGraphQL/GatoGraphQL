<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers\InterfaceType;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;

class IsCustomPostInterfaceTypeFieldResolver extends QueryableInterfaceTypeFieldResolver
{
    public function getImplementedInterfaceTypeFieldResolverClasses(): array
    {
        return [
            QueryableInterfaceTypeFieldResolver::class,
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return array_merge(
            parent::getFieldNamesToImplement(),
            [
                'datetime',
            ]
        );
    }

    public function getFieldTypeResolverClass(string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        $types = [
            'datetime' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver::class,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolverClass($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        /**
         * Please notice that the URL, slug, title and excerpt are nullable,
         * and content is not!
         */
        switch ($fieldName) {
            case 'datetime':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'datetime' => $this->translationAPI->__('Custom post published date and time', 'customposts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }
    public function getSchemaFieldArgs(string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($fieldName);
        switch ($fieldName) {
            case 'datetime':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'format',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                                $this->translationAPI->__('Date and time format, as defined in %s. Default value: \'%s\' (for current year date) or \'%s\' (otherwise)', 'customposts'),
                                'https://www.php.net/manual/en/function.date.php',
                                'j M, H:i',
                                'j M Y, H:i'
                            ),
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }
}
