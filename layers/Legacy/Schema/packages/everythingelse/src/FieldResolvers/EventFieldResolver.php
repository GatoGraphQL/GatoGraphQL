<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoPSchema\Events\TypeResolvers\ObjectType\EventTypeResolver;
use PoPSchema\Locations\TypeResolvers\ObjectType\LocationTypeResolver;

class EventFieldResolver extends AbstractDBDataFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            EventTypeResolver::class,
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

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        return match($fieldName) {
            'dates' => SchemaDefinition::TYPE_STRING,
            'times' => SchemaDefinition::TYPE_STRING,
            'startDateReadable' => SchemaDefinition::TYPE_STRING,
            'daterange' => SchemaDefinition::TYPE_OBJECT,
            'daterangetime' => SchemaDefinition::TYPE_OBJECT,
            default => parent::getSchemaFieldType($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
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
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'locations' => $this->translationAPI->__('Event\'s locations', 'events'),
            'categories' => $this->translationAPI->__('Event\'s categories', 'events'),
            'dates' => $this->translationAPI->__('Event\'s dates', 'events'),
            'times' => $this->translationAPI->__('Event\'s times', 'events'),
            'startDateReadable' => $this->translationAPI->__('Event\'s start date in human-readable format', 'events'),
            'daterange' => $this->translationAPI->__('Event\'s date range', 'events'),
            'daterangetime' => $this->translationAPI->__('Event\'s date range and time', 'events'),
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
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $event = $resultItem;
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

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'locations':
                return LocationTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
