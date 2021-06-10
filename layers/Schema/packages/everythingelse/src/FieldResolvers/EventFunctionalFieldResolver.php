<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Events\TypeResolvers\EventTypeResolver;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
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
            'multilayoutKeys',
            'latestcountsTriggerValues',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'multilayoutKeys' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'latestcountsTriggerValues' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'multilayoutKeys' => $this->translationAPI->__('', ''),
            'latestcountsTriggerValues' => $this->translationAPI->__('', ''),
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
