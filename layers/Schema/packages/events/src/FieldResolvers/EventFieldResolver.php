<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
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

    public static function getFieldNamesToResolve(): array
    {
        return [
            'location',
            'locations',
            'categories',
            'dates',
            'times',
            'startDate',
            'startDateReadable',
            'endDate',
            'allDay',
            'googlecalendar',
            'ical',
            'daterange',
            'daterangetime',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'location' => SchemaDefinition::TYPE_ID,
            'locations' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'categories' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'dates' => SchemaDefinition::TYPE_STRING,
            'times' => SchemaDefinition::TYPE_STRING,
            'startDate' => SchemaDefinition::TYPE_STRING,
            'startDateReadable' => SchemaDefinition::TYPE_STRING,
            'endDate' => SchemaDefinition::TYPE_STRING,
            'allDay' => SchemaDefinition::TYPE_BOOL,
            'googlecalendar' => SchemaDefinition::TYPE_URL,
            'ical' => SchemaDefinition::TYPE_URL,
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
            case 'startDate':
            case 'startDateReadable':
            case 'endDate':
            case 'allDay':
            case 'daterange':
            case 'daterangetime':
                return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'location' => $translationAPI->__('', ''),
            'locations' => $translationAPI->__('', ''),
            'categories' => $translationAPI->__('', ''),
            'dates' => $translationAPI->__('', ''),
            'times' => $translationAPI->__('', ''),
            'startDate' => $translationAPI->__('', ''),
            'startDateReadable' => $translationAPI->__('', ''),
            'endDate' => $translationAPI->__('', ''),
            'allDay' => $translationAPI->__('', ''),
            'googlecalendar' => $translationAPI->__('', ''),
            'ical' => $translationAPI->__('', ''),
            'daterange' => $translationAPI->__('', ''),
            'daterangetime' => $translationAPI->__('', ''),
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
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $posttagapi = \PoPSchema\PostTags\FunctionAPIFactory::getInstance();
        $cmstagsresolver = \PoPSchema\Tags\ObjectPropertyResolverFactory::getInstance();
        $event = $resultItem;
        switch ($fieldName) {
             // Override the url param to point to the original file
            case 'location':
                return $eventTypeAPI->getLocation($event);

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

            case 'startDate':
                return $eventTypeAPI->getStartDate($event);

            case 'startDateReadable':
                return $eventTypeAPI->getFormattedStartDate($event, 'd/m');

            case 'endDate':
                return $eventTypeAPI->getEndDate($event);

            case 'allDay':
                return $eventTypeAPI->isAllDay($event);

            case 'googlecalendar':
                return $eventTypeAPI->getGooglecalendarUrl($event);

            case 'ical':
                return $eventTypeAPI->getIcalUrl($event);

            case 'daterange':
                return array(
                    'from' => $eventTypeAPI->getFormattedStartDate($event, 'Y-m-d'),
                    'to' => $eventTypeAPI->getFormattedEndDate($event, 'Y-m-d'),
                    'readable' => $eventTypeAPI->getFormattedStartDate($event, 'd/m/Y') . GD_DATERANGE_SEPARATOR . $eventTypeAPI->getFormattedEndDate($event, 'd/m/Y'),
                );

            case 'daterangetime':
                return array(
                    'from' => $eventTypeAPI->getFormattedStartDate($event, 'Y-m-d'),
                    'to' => $eventTypeAPI->getFormattedEndDate($event, 'Y-m-d'),
                    'fromtime' => $eventTypeAPI->getFormattedStartDate($event, 'H:i'),
                    'totime' => $eventTypeAPI->getFormattedEndDate($event, 'H:i'),
                    'readable' => $eventTypeAPI->getFormattedStartDate($event, 'd/m/Y h:i A') . GD_DATERANGE_SEPARATOR . $eventTypeAPI->getFormattedEndDate($event, 'd/m/Y h:i A'),
                );
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'location':
            case 'locations':
                return LocationTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
