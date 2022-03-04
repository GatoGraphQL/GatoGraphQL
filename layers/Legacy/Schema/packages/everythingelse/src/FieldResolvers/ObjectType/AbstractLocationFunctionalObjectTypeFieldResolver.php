<?php

declare(strict_types=1);

namespace PoPCMSSchema\Locations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

abstract class AbstractLocationFunctionalObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
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
        return match($fieldName) {
            'locationsmapURL' => $this->getUrlScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
            'locationsmapURL' => $this->getTranslationAPI()->__('Locations map URL', 'pop-locations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'locationsmapURL':
                $locations = $objectTypeResolver->resolveValue($object, 'locations', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
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

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
