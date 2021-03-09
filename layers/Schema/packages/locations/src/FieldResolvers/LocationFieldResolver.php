<?php

declare(strict_types=1);

namespace PoPSchema\Locations\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Locations\TypeResolvers\LocationTypeResolver;

class LocationFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(LocationTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'coordinates',
            'name',
            'address',
            'city',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'coordinates' => SchemaDefinition::TYPE_OBJECT,
            'name' => SchemaDefinition::TYPE_STRING,
            'address' => SchemaDefinition::TYPE_STRING,
            'city' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'coordinates' => $translationAPI->__('Location coordinates', 'pop-locations'),
            'name' => $translationAPI->__('Location name', 'pop-locations'),
            'address' => $translationAPI->__('Location address', 'pop-locations'),
            'city' => $translationAPI->__('Location city', 'pop-locations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
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
        $pluginapi = \PoP_Locations_APIFactory::getInstance();
        $location = $resultItem;
        switch ($fieldName) {
            case 'coordinates':
                return array(
                    'lat' => $pluginapi->getLatitude($location),
                    'lng' => $pluginapi->getLongitude($location),
                );

            case 'name':
                return $pluginapi->getName($location);

            case 'address':
                return $pluginapi->getAddress($location);

            case 'city':
                return $pluginapi->getCity($location);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
