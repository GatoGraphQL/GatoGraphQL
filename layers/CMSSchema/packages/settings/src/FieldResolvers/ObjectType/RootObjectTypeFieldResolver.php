<?php

declare(strict_types=1);

namespace PoPCMSSchema\Settings\FieldResolvers\ObjectType;

use PoPCMSSchema\Settings\FeedbackItemProviders\FeedbackItemProvider;
use PoPCMSSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\IncludeExcludeFilterInputObjectTypeResolver;
use stdClass;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IncludeExcludeFilterInputObjectTypeResolver $includeExcludeFilterInputObjectTypeResolver = null;
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
    final protected function getIncludeExcludeFilterInputObjectTypeResolver(): IncludeExcludeFilterInputObjectTypeResolver
    {
        if ($this->includeExcludeFilterInputObjectTypeResolver === null) {
            /** @var IncludeExcludeFilterInputObjectTypeResolver */
            $includeExcludeFilterInputObjectTypeResolver = $this->instanceManager->getInstance(IncludeExcludeFilterInputObjectTypeResolver::class);
            $this->includeExcludeFilterInputObjectTypeResolver = $includeExcludeFilterInputObjectTypeResolver;
        }
        return $this->includeExcludeFilterInputObjectTypeResolver;
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
            'optionObjectValues',
            'optionNames',
            'options',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'optionValue' => $this->__('Single-value option saved in the DB, of any built-in scalar type, or `null` if entry does not exist', 'gatographql'),
            'optionValues' => $this->__('Array-value option saved in the DB, of any built-in scalar type, or `null` if entry does not exist', 'gatographql'),
            'optionObjectValue' => $this->__('Object-value option saved in the DB, or `null` if entry does not exist', 'gatographql'),
            'optionObjectValues' => $this->__('Array of object-value options saved in the DB, or `null` if entry does not exist', 'gatographql'),
            'optionNames' => $this->__('List of the allowed option names saved in the DB.', 'gatographql'),
            'options' => $this->__('JSON object, with the option name as key and the option value as value, for the provided option names.', 'gatographql'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'optionValue' => $this->getStringScalarTypeResolver(),
            'optionValues' => $this->getAnyBuiltInScalarScalarTypeResolver(),
            'optionObjectValue' => $this->getJSONObjectScalarTypeResolver(),
            'optionObjectValues' => $this->getJSONObjectScalarTypeResolver(),
            'optionNames' => $this->getStringScalarTypeResolver(),
            'options' => $this->getJSONObjectScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'optionValues',
            'optionObjectValues'
                => SchemaTypeModifiers::IS_ARRAY,
            'optionNames'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY | SchemaTypeModifiers::NON_NULLABLE,
            'options'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
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
            'optionObjectValue',
            'optionObjectValues'
                => [
                    'name' => $this->getStringScalarTypeResolver(),
                ],
            'optionNames'
                => [
                    'filterBy' => $this->getIncludeExcludeFilterInputObjectTypeResolver(),
                ],
            'options'
                => [
                    'names' => $this->getStringScalarTypeResolver(),
                ],
            default
                => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldArgName) {
            'name' => $this->__('The option name', 'gatographql'),
            'names' => $this->__('The option names', 'gatographql'),
            'filterBy' => $this->__('Filter the option names to be retrieved', 'gatographql'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ($fieldArgName) {
            'name' => SchemaTypeModifiers::MANDATORY,
            'names' => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
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
            case 'optionObjectValues':
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
            case 'options':
                $nonAllowedNames = [];
                /** @var string[] */
                $names = $fieldDataAccessor->getValue('names');
                foreach ($names as $name) {
                    if ($this->getSettingsTypeAPI()->validateIsOptionAllowed($name)) {
                        continue;
                    }
                    $nonAllowedNames[] = $name;
                }
                if ($nonAllowedNames !== []) {
                    $field = $fieldDataAccessor->getField();
                    if (count($nonAllowedNames) === 1) {
                        $objectTypeFieldResolutionFeedbackStore->addError(
                            new ObjectTypeFieldResolutionFeedback(
                                new FeedbackItemResolution(
                                    FeedbackItemProvider::class,
                                    FeedbackItemProvider::E1,
                                    [
                                        $nonAllowedNames[0],
                                    ]
                                ),
                                $field->getArgument('names') ?? $field,
                            )
                        );
                        break;
                    }
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                FeedbackItemProvider::class,
                                FeedbackItemProvider::E2,
                                [
                                    implode($this->__('\', \'', 'gatographql'), $nonAllowedNames),
                                ]
                            ),
                            $field->getArgument('names') ?? $field,
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
            case 'optionObjectValues':
                $value = $this->getSettingsTypeAPI()->getOption($fieldDataAccessor->getValue('name'));
                if ($value === null) {
                    return null;
                }
                if ($fieldDataAccessor->getFieldName() === 'optionValues') {
                    // Make sure keys are ints, not strings, otherwise it's an object
                    return array_values($value);
                }
                if ($fieldDataAccessor->getFieldName() === 'optionObjectValue') {
                    return is_array($value) ? (object) $value : $value;
                }
                if ($fieldDataAccessor->getFieldName() === 'optionObjectValues') {
                    return array_values(array_map(fn (mixed $valueItem) => is_array($valueItem) ? (object) $valueItem : $valueItem, $value));
                }
                return $value;
            case 'optionNames':
                $optionNames = [];
                $settingsTypeAPI = $this->getSettingsTypeAPI();
                foreach ($settingsTypeAPI->getOptionNames() as $optionName) {
                    if (!$settingsTypeAPI->validateIsOptionAllowed($optionName)) {
                        continue;
                    }
                    $optionNames[] = $optionName;
                }
                /** @var stdClass|null */
                $filterBy = $fieldDataAccessor->getValue('filterBy');
                return $this->filterOptionNames($optionNames, $filterBy);
            case 'options':
                $options = [];
                /** @var string[] */
                $names = $fieldDataAccessor->getValue('names');
                foreach ($names as $name) {
                    $options[$name] = $this->getSettingsTypeAPI()->getOption($name);
                }
                return (object) $options;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * @param string[] $optionNames
     * @return string[]
     */
    protected function filterOptionNames(array $optionNames, ?stdClass $filterBy): array
    {
        if ($filterBy === null) {
            return $optionNames;
        }
        if (isset($filterBy->include)) {
            /** @var string[] */
            $include = $filterBy->include;
            $optionNames = array_values(array_filter(
                $optionNames,
                fn (string $optionName) => $this->optionNameContainsAnyString($optionName, $include)
            ));
        } elseif (isset($filterBy->exclude)) {
            /** @var string[] */
            $exclude = $filterBy->exclude;
            $optionNames = array_values(array_filter(
                $optionNames,
                fn (string $optionName) => !$this->optionNameContainsAnyString($optionName, $exclude)
            ));
        }
        return $optionNames;
    }

    /**
     * @param string[] $strings
     */
    protected function optionNameContainsAnyString(string $optionName, array $strings): bool
    {
        foreach ($strings as $string) {
            if (str_contains($optionName, $string)) {
                return true;
            }
        }
        return false;
    }
}
