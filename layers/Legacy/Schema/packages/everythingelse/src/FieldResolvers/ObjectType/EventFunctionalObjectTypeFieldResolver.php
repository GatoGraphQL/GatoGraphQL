<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\WithEnumObjectTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoPSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;

class EventFunctionalObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use WithEnumObjectTypeFieldSchemaDefinitionResolverTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            EventObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'multilayoutKeys',
            'latestcountsTriggerValues',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'multilayoutKeys' => SchemaDefinition::TYPE_STRING,
            'latestcountsTriggerValues' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'multilayoutKeys',
            'latestcountsTriggerValues'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'multilayoutKeys' => $this->translationAPI->__('', ''),
            'latestcountsTriggerValues' => $this->translationAPI->__('', ''),
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
            case 'multilayoutKeys':
                // Override the "post" implementation: instead of depending on categories, depend on the scope of the event (future/current/past)
                $scope = $objectTypeResolver->resolveValue($event, 'scope', $variables, $expressions, $options);
                if (GeneralUtils::isError($scope)) {
                    return $scope;
                }
                $type = strtolower($objectTypeResolver->getTypeName());
                return array(
                    $type . '-' . $scope,
                    $type,
                );

            case 'latestcountsTriggerValues':
                $scope = $objectTypeResolver->resolveValue($event, 'scope', $variables, $expressions, $options);
                if (GeneralUtils::isError($scope)) {
                    return $scope;
                }
                $type = strtolower($objectTypeResolver->getTypeName());
                return array(
                    $type . '-' . $scope,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
