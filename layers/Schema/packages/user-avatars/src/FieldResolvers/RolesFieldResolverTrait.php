<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;

trait RolesFieldResolverTrait
{
    public function getFieldNamesToResolve(): array
    {
        return [
            'roles',
            'capabilities',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'roles',
            'capabilities',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'roles' => SchemaDefinition::TYPE_STRING,
            'capabilities' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'roles' => $translationAPI->__('All user avatars', 'user-avatars'),
            'capabilities' => $translationAPI->__('All user capabilities', 'user-avatars'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
}
