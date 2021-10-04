<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoPSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;
use PoPSchema\Locations\TypeResolvers\ObjectType\LocationObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\ObjectScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class EventObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected ObjectScalarTypeResolver $objectScalarTypeResolver;
    protected LocationObjectTypeResolver $locationObjectTypeResolver;
    
    #[Required]
    final public function autowireEventObjectTypeFieldResolver(
        StringScalarTypeResolver $stringScalarTypeResolver,
        ObjectScalarTypeResolver $objectScalarTypeResolver,
        LocationObjectTypeResolver $locationObjectTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->objectScalarTypeResolver = $objectScalarTypeResolver;
        $this->locationObjectTypeResolver = $locationObjectTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            EventObjectTypeResolver::class,
        ];
    }

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
            'dates' => $this->stringScalarTypeResolver,
            'times' => $this->stringScalarTypeResolver,
            'startDateReadable' => $this->stringScalarTypeResolver,
            'daterange' => $this->objectScalarTypeResolver,
            'daterangetime' => $this->objectScalarTypeResolver,
            'locations' => $this->locationObjectTypeResolver,
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
            'locations' => $this->translationAPI->__('Event\'s locations', 'events'),
            'categories' => $this->translationAPI->__('Event\'s categories', 'events'),
            'dates' => $this->translationAPI->__('Event\'s dates', 'events'),
            'times' => $this->translationAPI->__('Event\'s times', 'events'),
            'startDateReadable' => $this->translationAPI->__('Event\'s start date in human-readable format', 'events'),
            'daterange' => $this->translationAPI->__('Event\'s date range', 'events'),
            'daterangetime' => $this->translationAPI->__('Event\'s date range and time', 'events'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
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
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $event = $object;
        switch ($fieldName) {
             // Override 'locations' field
            case 'locations':
                // Events can have no location
                $value = array();
                $location = $objectTypeResolver->resolveValue($event, 'location', $variables, $expressions, $options);
                if (GeneralUtils::isError($location)) {
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

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
