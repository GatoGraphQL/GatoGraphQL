<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoPSchema\Locations\TypeResolvers\LocationTypeResolver;
use PoPSchema\Events\TypeResolvers\EventTypeResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

class EventFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(EventTypeResolver::class);
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

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'locations' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'categories' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'dates' => SchemaDefinition::TYPE_STRING,
            'times' => SchemaDefinition::TYPE_STRING,
            'startDateReadable' => SchemaDefinition::TYPE_STRING,
            'daterange' => SchemaDefinition::TYPE_OBJECT,
            'daterangetime' => SchemaDefinition::TYPE_OBJECT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'categories':
            case 'dates':
            case 'times':
            case 'startDateReadable':
            case 'daterange':
            case 'daterangetime':
                return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
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
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $event = $resultItem;
        switch ($fieldName) {
             // Override 'locations' field
            case 'locations':
                // Events can have no location
                $value = array();
                $location = $typeResolver->resolveValue($event, 'location', $variables, $expressions, $options);
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

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'locations':
                return LocationTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
