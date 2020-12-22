<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldInterfaceResolvers;

use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\AbstractSchemaFieldInterfaceResolver;

class WithAuthorFieldInterfaceResolver extends AbstractSchemaFieldInterfaceResolver
{
    public const NAME = 'WithAuthor';

    public function getInterfaceName(): string
    {
        return self::NAME;
    }

    public function getSchemaInterfaceDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Entities that have an author', 'queriedobject');
    }

    public static function getFieldNamesToImplement(): array
    {
        return [
            'author',
        ];
    }

    public function getSchemaFieldType(string $fieldName): ?string
    {
        $types = [
            'author' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function isSchemaFieldResponseNonNullable(string $fieldName): bool
    {
        switch ($fieldName) {
            case 'author':
                return true;
        }
        return parent::isSchemaFieldResponseNonNullable($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'author' => $translationAPI->__('The entity\'s author', 'queriedobject'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }

    /**
     * This function is not called by the engine, to generate the schema.
     * Instead, the resolver is obtained from the fieldResolver.
     * To make sure that all fieldResolvers implementing the same interface
     * return the expected type for the field, they can obtain it from the
     * interface through this function.
     */
    public function getFieldTypeResolverClass(string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'author':
                return UserTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($fieldName);
    }
}
