<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\FieldResolvers\EnumTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoPSchema\Events\TypeResolvers\EventTypeResolver;

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
            'multilayoutKeys' => SchemaDefinition::TYPE_STRING,
            'latestcountsTriggerValues' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'multilayoutKeys',
            'latestcountsTriggerValues'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
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
