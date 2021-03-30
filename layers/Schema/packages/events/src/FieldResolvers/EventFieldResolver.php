<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Locations\TypeResolvers\LocationTypeResolver;
use PoPSchema\Events\TypeResolvers\EventTypeResolver;
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
            'location',
            'startDate',
            'endDate',
            'isAllDay',
            'googlecalendar',
            'ical',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'location' => SchemaDefinition::TYPE_ID,
            'startDate' => SchemaDefinition::TYPE_STRING,
            'endDate' => SchemaDefinition::TYPE_STRING,
            'isAllDay' => SchemaDefinition::TYPE_BOOL,
            'googlecalendar' => SchemaDefinition::TYPE_URL,
            'ical' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'startDate':
            case 'endDate':
            case 'isAllDay':
                return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'location' => $translationAPI->__('Event\'s location', 'events'),
            'startDate' => $translationAPI->__('Event\'s start ate', 'events'),
            'endDate' => $translationAPI->__('Event\'s end date', 'events'),
            'isAllDay' => $translationAPI->__('Is the event all day long?', 'events'),
            'googlecalendar' => $translationAPI->__('Event\'s Google calendar URL', 'events'),
            'ical' => $translationAPI->__('Event\'s Ical URL', 'events'),
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
             // Override the url param to point to the original file
            case 'location':
                return $eventTypeAPI->getLocation($event);

            case 'startDate':
                return $eventTypeAPI->getStartDate($event);

            case 'endDate':
                return $eventTypeAPI->getEndDate($event);

            case 'isAllDay':
                return $eventTypeAPI->isAllDay($event);

            case 'googlecalendar':
                return $eventTypeAPI->getGooglecalendarUrl($event);

            case 'ical':
                return $eventTypeAPI->getIcalUrl($event);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'location':
                return LocationTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
