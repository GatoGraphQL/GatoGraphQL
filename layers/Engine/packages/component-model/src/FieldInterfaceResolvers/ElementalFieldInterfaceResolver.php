<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\AbstractSchemaFieldInterfaceResolver;

class ElementalFieldInterfaceResolver extends AbstractSchemaFieldInterfaceResolver
{
    public const NAME = 'Elemental';
    public function getInterfaceName(): string
    {
        return self::NAME;
    }

    public function getSchemaInterfaceDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('The fundamental fields that must be implemented by all objects', 'component-model');
    }

    public static function getFieldNamesToImplement(): array
    {
        return [
            'id',
        ];
    }

    public function getSchemaFieldType(string $fieldName): ?string
    {
        $types = [
            'id' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function isSchemaFieldResponseNonNullable(string $fieldName): bool
    {
        switch ($fieldName) {
            case 'id':
                return true;
        }
        return parent::isSchemaFieldResponseNonNullable($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'id' => $translationAPI->__('The object\'s unique identifier for its type', 'component-model'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }
}
