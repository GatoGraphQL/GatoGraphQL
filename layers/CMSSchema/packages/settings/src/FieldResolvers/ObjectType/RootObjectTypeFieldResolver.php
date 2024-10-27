<?php

declare(strict_types=1);

namespace PoPCMSSchema\Settings\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoPCMSSchema\Settings\FeedbackItemProviders\FeedbackItemProvider;
use PoPCMSSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?SettingsTypeAPIInterface $settingsTypeAPI = null;

    final protected function getAnyBuiltInScalarScalarTypeResolver(): AnyBuiltInScalarScalarTypeResolver
    {
        if ($this->anyBuiltInScalarScalarTypeResolver === null) {
            /** @var AnyBuiltInScalarScalarTypeResolver */
            $anyBuiltInScalarScalarTypeResolver = $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
            $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
        }
        return $this->anyBuiltInScalarScalarTypeResolver;
    }
    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        if ($this->jsonObjectScalarTypeResolver === null) {
            /** @var JSONObjectScalarTypeResolver */
            $jsonObjectScalarTypeResolver = $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
            $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
        }
        return $this->jsonObjectScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getSettingsTypeAPI(): SettingsTypeAPIInterface
    {
        if ($this->settingsTypeAPI === null) {
            /** @var SettingsTypeAPIInterface */
            $settingsTypeAPI = $this->instanceManager->getInstance(SettingsTypeAPIInterface::class);
            $this->settingsTypeAPI = $settingsTypeAPI;
        }
        return $this->settingsTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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
            'optionValue' => $this->__('Single-value option saved in the DB, of any built-in scalar type, or `null` if entry does not exist', 'pop-settings'),
            'optionValues' => $this->__('Array-value option saved in the DB, of any built-in scalar type, or `null` if entry does not exist', 'pop-settings'),
            'optionObjectValue' => $this->__('Object-value option saved in the DB, or `null` if entry does not exist', 'pop-settings'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'optionValue' => $this->getStringScalarTypeResolver(),
            'optionValues' => $this->getAnyBuiltInScalarScalarTypeResolver(),
            'optionObjectValue' => $this->getJSONObjectScalarTypeResolver(),
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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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

    /**
     * Custom validations
     */
    public function validateFieldKeyValues(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validateFieldKeyValues($objectTypeResolver, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'optionValue':
            case 'optionValues':
            case 'optionObjectValue':
                if (!$this->getSettingsTypeAPI()->validateIsOptionAllowed($fieldDataAccessor->getValue('name'))) {
                    $field = $fieldDataAccessor->getField();
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                FeedbackItemProvider::class,
                                FeedbackItemProvider::E1,
                                [
                                    $fieldDataAccessor->getValue('name'),
                                ]
                            ),
                            $field->getArgument('name') ?? $field,
                        )
                    );
                }
                break;
        }
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'optionValue':
            case 'optionValues':
            case 'optionObjectValue':
                $value = $this->getSettingsTypeAPI()->getOption($fieldDataAccessor->getValue('name'));
                if ($value === null) {
                    return null;
                }
                if ($fieldDataAccessor->getFieldName() === 'optionValues') {
                    // Make sure keys are ints, not strings, otherwise it's an object
                    return array_values($value);
                }
                if ($fieldDataAccessor->getFieldName() === 'optionObjectValue') {
                    return (object) $value;
                }
                return $value;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
