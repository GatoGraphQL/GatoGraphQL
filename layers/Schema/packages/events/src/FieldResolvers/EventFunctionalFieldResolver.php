<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers;

use PoPSchema\Events\Enums\EventScopeEnum;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Events\TypeResolvers\EventTypeResolver;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\FieldResolvers\EnumTypeFieldSchemaDefinitionResolverTrait;

class EventFunctionalFieldResolver extends AbstractFunctionalFieldResolver
{
    use EnumTypeFieldSchemaDefinitionResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(EventTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'scope',
            'multilayoutKeys',
            'latestcountsTriggerValues',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'scope' => SchemaDefinition::TYPE_ENUM,
            'multilayoutKeys' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'latestcountsTriggerValues' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'scope' => $translationAPI->__('', ''),
            'multilayoutKeys' => $translationAPI->__('', ''),
            'latestcountsTriggerValues' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    protected function getSchemaDefinitionEnumName(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'scope':
                /**
                 * @var EventScopeEnum
                 */
                $eventScopeEnum = $instanceManager->getInstance(EventScopeEnum::class);
                return $eventScopeEnum->getName();
        }
        return null;
    }

    protected function getSchemaDefinitionEnumValues(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'scope':
                /**
                 * @var EventScopeEnum
                 */
                $eventScopeEnum = $instanceManager->getInstance(EventScopeEnum::class);
                return $eventScopeEnum->getValues();
        }
        return null;
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
            case 'scope':
                if ($eventTypeAPI->isFutureEvent($typeResolver->getID($event))) {
                    return \POP_EVENTS_SCOPE_FUTURE;
                } elseif ($eventTypeAPI->isCurrentEvent($typeResolver->getID($event))) {
                    return \POP_EVENTS_SCOPE_CURRENT;
                }
                return \POP_EVENTS_SCOPE_PAST;

            case 'multilayoutKeys':
                // Override the "post" implementation: instead of depending on categories, depend on the scope of the event (future/current/past)
                $scope = $typeResolver->resolveValue($event, 'scope', $variables, $expressions, $options);
                if (GeneralUtils::isError($scope)) {
                    return $scope;
                }
                $type = strtolower($typeResolver->getTypeName());
                return array(
                    $type . '-' . $scope,
                    $type,
                );

            case 'latestcountsTriggerValues':
                $scope = $typeResolver->resolveValue($event, 'scope', $variables, $expressions, $options);
                if (GeneralUtils::isError($scope)) {
                    return $scope;
                }
                $type = strtolower($typeResolver->getTypeName());
                return array(
                    $type . '-' . $scope,
                );
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
