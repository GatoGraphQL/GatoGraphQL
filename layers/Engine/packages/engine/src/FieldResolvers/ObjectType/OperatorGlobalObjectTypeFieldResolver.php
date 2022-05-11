<?php

declare(strict_types=1);

namespace PoP\Engine\FieldResolvers\ObjectType;

use ArgumentCountError;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractGlobalObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\Exception\RuntimeOperationException;
use PoP\Engine\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\Engine\HelperServices\ArrayTraversionHelperServiceInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class OperatorGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?ArrayTraversionHelperServiceInterface $arrayTraversionHelperService = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setArrayTraversionHelperService(ArrayTraversionHelperServiceInterface $arrayTraversionHelperService): void
    {
        $this->arrayTraversionHelperService = $arrayTraversionHelperService;
    }
    final protected function getArrayTraversionHelperService(): ArrayTraversionHelperServiceInterface
    {
        return $this->arrayTraversionHelperService ??= $this->instanceManager->getInstance(ArrayTraversionHelperServiceInterface::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'if',
            'not',
            'and',
            'or',
            'equals',
            'empty',
            'isNull',
            'extract',
            'time',
            'echo',
            'sprintf',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'if' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
            'not' => $this->getBooleanScalarTypeResolver(),
            'and' => $this->getBooleanScalarTypeResolver(),
            'or' => $this->getBooleanScalarTypeResolver(),
            'equals' => $this->getBooleanScalarTypeResolver(),
            'empty' => $this->getBooleanScalarTypeResolver(),
            'isNull' => $this->getBooleanScalarTypeResolver(),
            'extract' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
            'time' => $this->getIntScalarTypeResolver(),
            'echo' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
            'sprintf' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'not',
            'and',
            'or',
            'equals',
            'empty',
            'isNull',
            'time',
            'sprintf'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'if' => $this->__('If a boolean property is true, execute a field, else, execute another field', 'engine'),
            'not' => $this->__('Return the opposite value of a boolean property', 'engine'),
            'and' => $this->__('Return an `AND` operation among several boolean properties', 'engine'),
            'or' => $this->__('Return an `OR` operation among several boolean properties', 'engine'),
            'equals' => $this->__('Indicate if the result from a field equals a certain value', 'engine'),
            'empty' => $this->__('Indicate if a value is empty', 'engine'),
            'isNull' => $this->__('Indicate if a value is null', 'engine'),
            'extract' => $this->__('Given an object, it retrieves the data under a certain path', 'engine'),
            'time' => $this->__('Return the time now (https://php.net/manual/en/function.time.php)', 'engine'),
            'echo' => $this->__('Repeat back the input, whatever it is', 'engine'),
            'sprintf' => $this->__('Replace placeholders inside a string with provided values', 'engine'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'if' => [
                'condition' => $this->getBooleanScalarTypeResolver(),
                'then' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
                'else' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
            ],
            'not' => [
                'value' => $this->getBooleanScalarTypeResolver(),
            ],
            'and',
            'or' => [
                'values' => $this->getBooleanScalarTypeResolver(),
            ],
            'equals' => [
                'value1' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
                'value2' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
            ],
            'empty' => [
                'value' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
            ],
            'isNull' => [
                'value' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
            ],
            'extract' => [
                'object' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
                'path' => $this->getStringScalarTypeResolver(),
            ],
            'echo' => [
                'value' => $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver(),
            ],
            'sprintf' => [
                'string' => $this->getStringScalarTypeResolver(),
                'values' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['if' => 'condition'] => $this->__('The condition to check if its value is `true` or `false`', 'engine'),
            ['if' => 'then'] => $this->__('The value to return if the condition evals to `true`', 'engine'),
            ['if' => 'else'] => $this->__('The value to return if the condition evals to `false`', 'engine'),
            ['not' => 'value'] => $this->__('The value from which to return its opposite value', 'engine'),
            ['and' => 'values'],
            ['or' => 'values'] => sprintf(
                $this->__('The array of values on which to execute the `%s` operation', 'engine'),
                strtoupper($fieldName)
            ),
            ['equals' => 'value1'] => $this->__('The first value to compare', 'engine'),
            ['equals' => 'value2'] => $this->__('The second value to compare', 'engine'),
            ['empty' => 'value'] => $this->__('The value to check if it is empty', 'engine'),
            ['isNull' => 'value'] => $this->__('The value to check if it is null', 'engine'),
            ['extract' => 'object'] => $this->__('The object to retrieve the data from', 'engine'),
            ['extract' => 'path'] => $this->__('The path to retrieve data from the object. Paths are separated with \'.\' for each sublevel', 'engine'),
            ['echo' => 'value'] => $this->__('The input to be echoed back', 'engine'),
            ['sprintf' => 'string'] => $this->__('The string containing the placeholders', 'engine'),
            ['sprintf' => 'values'] => $this->__('The values to replace the placeholders with inside the string', 'engine'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['if' => 'condition'],
            ['if' => 'then'],
            ['not' => 'value'],
            ['equals' => 'value1'],
            ['equals' => 'value2'],
            ['empty' => 'value'],
            ['extract' => 'object'],
            ['extract' => 'path'],
            ['echo' => 'value'],
            ['sprintf' => 'string']
                => SchemaTypeModifiers::MANDATORY,
            ['and' => 'values'],
            ['or' => 'values'],
            ['sprintf' => 'values']
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'if':
                if ($fieldArgs['condition']) {
                    return $fieldArgs['then'];
                } elseif (isset($fieldArgs['else'])) {
                    return $fieldArgs['else'];
                }
                return null;
            case 'not':
                return !$fieldArgs['value'];
            case 'and':
                return array_reduce($fieldArgs['values'], function ($accumulated, $value) {
                    $accumulated = $accumulated && $value;
                    return $accumulated;
                }, true);
            case 'or':
                return array_reduce($fieldArgs['values'], function ($accumulated, $value) {
                    $accumulated = $accumulated || $value;
                    return $accumulated;
                }, false);
            case 'equals':
                return $fieldArgs['value1'] == $fieldArgs['value2'];
            case 'empty':
                return empty($fieldArgs['value']);
            case 'isNull':
                return is_null($fieldArgs['value']);
            case 'extract':
                try {
                    $array = (array) $fieldArgs['object'];
                    $pointerToArrayItemUnderPath = $this->getArrayTraversionHelperService()->getPointerToElementItemUnderPath($array, $fieldArgs['path']);
                } catch (RuntimeOperationException $e) {
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E7,
                                [
                                    $e->getMessage(),
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $objectTypeResolver,
                        )
                    );
                    return null;
                }
                return $pointerToArrayItemUnderPath;
            case 'time':
                return time();
            case 'echo':
                return $fieldArgs['value'];
            case 'sprintf':
                // If more "%s" are passed than replacements provided, then `sprintf`
                // will throw an ArgumentCountError. Catch it and return an error instead.
                try {
                    return sprintf($fieldArgs['string'], ...$fieldArgs['values']);
                } catch (ArgumentCountError $e) {
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E7,
                                [
                                    $e->getMessage(),
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $objectTypeResolver,
                        )
                    );
                    return null;
                }
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
