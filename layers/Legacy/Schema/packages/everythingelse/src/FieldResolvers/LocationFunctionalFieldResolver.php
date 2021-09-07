<?php

declare(strict_types=1);

namespace PoPSchema\Locations\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoPSchema\Locations\TypeResolvers\Object\LocationTypeResolver;

class LocationFunctionalFieldResolver extends AbstractFunctionalFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            LocationTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'mapURL',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'mapURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'mapURL',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'mapURL' => $this->translationAPI->__('Location map URL', 'pop-locations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'mapURL':
                // Decode it, because add_query_arg sends the params encoded and it doesn't look nice
                return urldecode(GeneralUtils::addQueryArgs([
                    POP_INPUTNAME_LOCATIONID => [$relationalTypeResolver->getID($resultItem)],
                ], RouteUtils::getRouteURL(POP_LOCATIONS_ROUTE_LOCATIONSMAP)));
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
