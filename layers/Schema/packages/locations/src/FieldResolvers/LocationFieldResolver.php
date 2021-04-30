<?php

declare(strict_types=1);

namespace PoPSchema\Locations\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Locations\Facades\LocationTypeAPIFacade;
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
        $descriptions = [
            'coordinates' => $this->translationAPI->__('Location coordinates', 'pop-locations'),
            'name' => $this->translationAPI->__('Location name', 'pop-locations'),
            'address' => $this->translationAPI->__('Location address', 'pop-locations'),
            'city' => $this->translationAPI->__('Location city', 'pop-locations'),
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
        $locationTypeAPI = LocationTypeAPIFacade::getInstance();
        $location = $resultItem;
        switch ($fieldName) {
            case 'coordinates':
                return array(
                    'lat' => $locationTypeAPI->getLatitude($location),
                    'lng' => $locationTypeAPI->getLongitude($location),
                );

            case 'name':
                return $locationTypeAPI->getName($location);

            case 'address':
                return $locationTypeAPI->getAddress($location);

            case 'city':
                return $locationTypeAPI->getCity($location);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
