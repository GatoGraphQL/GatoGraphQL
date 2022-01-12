<?php

declare(strict_types=1);

namespace PoPSchema\Settings\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoPSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?SettingsTypeAPIInterface $settingsTypeAPI = null;

    final public function setAnyBuiltInScalarScalarTypeResolver(AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver): void
    {
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
    }
    final protected function getAnyBuiltInScalarScalarTypeResolver(): AnyBuiltInScalarScalarTypeResolver
    {
        return $this->anyBuiltInScalarScalarTypeResolver ??= $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
    }
    final public function setJSONObjectScalarTypeResolver(JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver): void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        return $this->jsonObjectScalarTypeResolver ??= $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setSettingsTypeAPI(SettingsTypeAPIInterface $settingsTypeAPI): void
    {
        $this->settingsTypeAPI = $settingsTypeAPI;
    }
    final protected function getSettingsTypeAPI(): SettingsTypeAPIInterface
    {
        return $this->settingsTypeAPI ??= $this->instanceManager->getInstance(SettingsTypeAPIInterface::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'optionValue',
            'optionValues',
            'optionObjectValue',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'optionValue' => $this->__('Single-value option saved in the DB, of any built-in scalar type', 'pop-settings'),
            'optionValues' => $this->__('Array-value option saved in the DB, of any built-in scalar type', 'pop-settings'),
            'optionObjectValue' => $this->__('Object-value option saved in the DB', 'pop-settings'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'optionValue',
            'optionValues'
                => $this->getAnyBuiltInScalarScalarTypeResolver(),
            'optionObjectValue'
                => $this->getJSONObjectScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'optionValues' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'optionValue',
            'optionValues',
            'optionObjectValue'
                => [
                    'name' => $this->getStringScalarTypeResolver(),
                ],
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldArgName) {
            'name' => $this->__('The option name', 'pop-settings'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ($fieldArgName) {
            'name' => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    protected function doResolveSchemaValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs
    ): array {
        // if (!FieldQueryUtils::isAnyFieldArgumentValueAField($fieldArgs)) {
        switch ($fieldName) {
            case 'optionValue':
            case 'optionValues':
            case 'optionObjectValue':
                if (!$this->getSettingsTypeAPI()->validateIsOptionAllowed($fieldArgs['name'])) {
                    return [
                        sprintf(
                            $this->__('There is no option with name \'%s\'', 'settings'),
                            $fieldArgs['name']
                        ),
                    ];
                }
                break;
        }
        // }

        return parent::doResolveSchemaValidationErrorDescriptions($objectTypeResolver, $fieldName, $fieldArgs);
    }

    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
    ): bool {
        switch ($fieldName) {
            case 'optionValue':
            case 'optionValues':
            case 'optionObjectValue':
                return true;
        }
        return parent::validateResolvedFieldType(
            $objectTypeResolver,
            $fieldName,
            $fieldArgs,
        );
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
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'optionValue':
            case 'optionValues':
            case 'optionObjectValue':
                $value = $this->getSettingsTypeAPI()->getOption($fieldArgs['name']);
                if ($fieldName === 'optionValues') {
                    return (array) $value;
                }
                if ($fieldName === 'optionObjectValue') {
                    return (object) $value;
                }
                return $value;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
