<?php

declare(strict_types=1);

namespace PoPSchema\Locations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

abstract class AbstractLocationFunctionalObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected URLScalarTypeResolver $URLScalarTypeResolver,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
    }

    protected function getDbobjectIdField()
    {
        return null;
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'locationsmapURL',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'locationsmapURL' => $this->URLScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'locationsmapURL' => $this->translationAPI->__('Locations map URL', 'pop-locations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'locationsmapURL':
                $locations = $objectTypeResolver->resolveValue($object, 'locations', $variables, $expressions, $options);
                if (GeneralUtils::isError($locations)) {
                    return null;
                }
                return
                    // Decode it, because add_query_arg sends the params encoded and it doesn't look nice
                    urldecode(
                        // Add param "lid[]=..."
                        GeneralUtils::addQueryArgs([
                            POP_INPUTNAME_LOCATIONID => $locations,
                            // In order to keep always the same layout for the same URL, we add the param of which object we are coming from
                            // (Then, in the modal map, it will show either post/user layout, and that layout will be cached for that post/user but not for other objects)
                            $this->getDbobjectIdField() => $objectTypeResolver->getID($object),
                        ], RouteUtils::getRouteURL(POP_LOCATIONS_ROUTE_LOCATIONSMAP))
                    );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
