<?php

declare(strict_types=1);

namespace PoPCMSSchema\Events\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;
use PoPCMSSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;
use PoPCMSSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;

class EventObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;
    private ?LocationObjectTypeResolver $locationObjectTypeResolver = null;
    
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setJSONObjectScalarTypeResolver(JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver): void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        if ($this->jsonObjectScalarTypeResolver === null) {
            /** @var JSONObjectScalarTypeResolver */
            $jsonObjectScalarTypeResolver = $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
            $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
        }
        return $this->jsonObjectScalarTypeResolver;
    }
    final public function setLocationObjectTypeResolver(LocationObjectTypeResolver $locationObjectTypeResolver): void
    {
        $this->locationObjectTypeResolver = $locationObjectTypeResolver;
    }
    final protected function getLocationObjectTypeResolver(): LocationObjectTypeResolver
    {
        if ($this->locationObjectTypeResolver === null) {
            /** @var LocationObjectTypeResolver */
            $locationObjectTypeResolver = $this->instanceManager->getInstance(LocationObjectTypeResolver::class);
            $this->locationObjectTypeResolver = $locationObjectTypeResolver;
        }
        return $this->locationObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            EventObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'locations',
            'categories',
            'dates',
            'times',
            'startDateReadable',
            'daterange',
            'daterangetime',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'dates' => $this->getStringScalarTypeResolver(),
            'times' => $this->getStringScalarTypeResolver(),
            'startDateReadable' => $this->getStringScalarTypeResolver(),
            'daterange' => $this->getJsonObjectScalarTypeResolver(),
            'daterangetime' => $this->getJsonObjectScalarTypeResolver(),
            'locations' => $this->getLocationObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match($fieldName) {
            'dates',
            'times',
            'startDateReadable',
            'daterange',
            'daterangetime'
                => SchemaTypeModifiers::NON_NULLABLE,
            'locations'
                => SchemaTypeModifiers::IS_ARRAY,
            'categories'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
            'locations' => $this->getTranslationAPI()->__('Event\'s locations', 'events'),
            'categories' => $this->getTranslationAPI()->__('Event\'s categories', 'events'),
            'dates' => $this->getTranslationAPI()->__('Event\'s dates', 'events'),
            'times' => $this->getTranslationAPI()->__('Event\'s times', 'events'),
            'startDateReadable' => $this->getTranslationAPI()->__('Event\'s start date in human-readable format', 'events'),
            'daterange' => $this->getTranslationAPI()->__('Event\'s date range', 'events'),
            'daterangetime' => $this->getTranslationAPI()->__('Event\'s date range and time', 'events'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $event = $object;
        switch ($fieldDataAccessor->getFieldName()) {
             // Override 'locations' field
            case 'locations':
                // Events can have no location
                $value = array();
                $location = $objectTypeResolver->resolveValue(
                    $event,
                    new LeafField(
                        'location',
                        null,
                        [],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                );
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return $location;
                } elseif ($location) {
                    $value[] = $location;
                }
                return $value;

             // Override
            case 'categories':
                return array_keys($eventTypeAPI->getCategories($event));

            case 'dates':
                return $eventTypeAPI->getDates($event);

            case 'times':
                return $eventTypeAPI->getTimes($event);

            case 'startDateReadable':
                return $eventTypeAPI->getFormattedStartDate($event, 'd/m');

            case 'daterange':
                return array(
                    'from' => $eventTypeAPI->getFormattedStartDate($event, 'Y-m-d'),
                    'to' => $eventTypeAPI->getFormattedEndDate($event, 'Y-m-d'),
                    'readable' => $eventTypeAPI->getFormattedStartDate($event, 'd/m/Y') . ' - ' . $eventTypeAPI->getFormattedEndDate($event, 'd/m/Y'),
                );

            case 'daterangetime':
                return array(
                    'from' => $eventTypeAPI->getFormattedStartDate($event, 'Y-m-d'),
                    'to' => $eventTypeAPI->getFormattedEndDate($event, 'Y-m-d'),
                    'fromtime' => $eventTypeAPI->getFormattedStartDate($event, 'H:i'),
                    'totime' => $eventTypeAPI->getFormattedEndDate($event, 'H:i'),
                    'readable' => $eventTypeAPI->getFormattedStartDate($event, 'd/m/Y h:i A') . ' - ' . $eventTypeAPI->getFormattedEndDate($event, 'd/m/Y h:i A'),
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
