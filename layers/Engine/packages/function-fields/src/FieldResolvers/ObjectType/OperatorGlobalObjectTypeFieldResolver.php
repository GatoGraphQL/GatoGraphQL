<?php

declare(strict_types=1);

namespace PoP\FunctionFields\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractGlobalObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\FloatScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\FunctionFields\Component;
use PoP\FunctionFields\ComponentConfiguration;
use PoP\FunctionFields\TypeResolvers\ScalarType\ArrayKeyScalarTypeResolver;
use PoP\Root\App;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;

class OperatorGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    private ?FloatScalarTypeResolver $floatScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver = null;
    private ?ArrayKeyScalarTypeResolver $arrayKeyScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;

    final public function setFloatScalarTypeResolver(FloatScalarTypeResolver $floatScalarTypeResolver): void
    {
        $this->floatScalarTypeResolver = $floatScalarTypeResolver;
    }
    final protected function getFloatScalarTypeResolver(): FloatScalarTypeResolver
    {
        return $this->floatScalarTypeResolver ??= $this->instanceManager->getInstance(FloatScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setAnyBuiltInScalarScalarTypeResolver(AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver): void
    {
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
    }
    final protected function getAnyBuiltInScalarScalarTypeResolver(): AnyBuiltInScalarScalarTypeResolver
    {
        return $this->anyBuiltInScalarScalarTypeResolver ??= $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
    }
    final public function setArrayKeyScalarTypeResolver(ArrayKeyScalarTypeResolver $arrayKeyScalarTypeResolver): void
    {
        $this->arrayKeyScalarTypeResolver = $arrayKeyScalarTypeResolver;
    }
    final protected function getArrayKeyScalarTypeResolver(): ArrayKeyScalarTypeResolver
    {
        return $this->arrayKeyScalarTypeResolver ??= $this->instanceManager->getInstance(ArrayKeyScalarTypeResolver::class);
    }
    final public function setJSONObjectScalarTypeResolver(JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver): void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        return $this->jsonObjectScalarTypeResolver ??= $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'concat',
            'divide',
            'arrayRandom',
            'arrayJoin',
            'arrayItem',
            'arraySearch',
            'arrayFill',
            'arrayValues',
            'arrayUnique',
            'arrayDiff',
            'arrayAddItem',
            'arrayAsQueryStr',
            'objectAsQueryStr',
            'upperCase',
            'lowerCase',
            'titleCase',
        ];
    }

    public function isServiceEnabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return !$componentConfiguration->disableFunctionFields();
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'divide'
                => $this->getFloatScalarTypeResolver(),
            'concat',
            'arrayJoin',
            'arrayAsQueryStr',
            'objectAsQueryStr',
            'upperCase',
            'lowerCase',
            'titleCase'
                => $this->getStringScalarTypeResolver(),
            'arraySearch'
                => $this->getAnyBuiltInScalarScalarTypeResolver(),
            'arrayRandom',
            'arrayItem',
            'arrayFill',
            'arrayValues',
            'arrayUnique',
            'arrayDiff',
            'arrayAddItem'
                => $this->getDangerouslyDynamicScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'concat',
            'divide',
            'arrayRandom',
            'arrayJoin',
            'arrayItem',
            'arraySearch',
            'arrayAsQueryStr',
            'objectAsQueryStr',
            'upperCase',
            'lowerCase',
            'titleCase'
                => SchemaTypeModifiers::NON_NULLABLE,
            'arrayFill',
            'arrayValues',
            'arrayUnique',
            'arrayDiff',
            'arrayAddItem'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'concat' => $this->__('Concatenate two or more strings', 'function-fields'),
            'divide' => $this->__('Divide a number by another number', 'function-fields'),
            'arrayRandom' => $this->__('Randomly select one element from the provided ones', 'function-fields'),
            'arrayJoin' => $this->__('Join all the strings in an array, using a provided separator', 'function-fields'),
            'arrayItem' => $this->__('Access the element on the given position in the array', 'function-fields'),
            'arraySearch' => $this->__('Search in what position is an element placed in the array. If found, it returns its position (integer), otherwise it returns `false` (boolean)', 'function-fields'),
            'arrayFill' => $this->__('Fill a target array with elements from a source array, where a certain property is the same', 'function-fields'),
            'arrayValues' => $this->__('Return the values from a two-dimensional array', 'function-fields'),
            'arrayUnique' => $this->__('Filters out all duplicated elements in the array', 'function-fields'),
            'arrayDiff' => $this->__('Return an array containing all the elements from the first array which are not present on any of the other arrays', 'function-fields'),
            'arrayAddItem' => $this->__('Adds an element to the array', 'function-fields'),
            'arrayAsQueryStr' => $this->__('Represent an array as a string', 'function-fields'),
            'objectAsQueryStr' => $this->__('Represent an object as a string', 'function-fields'),
            'upperCase' => $this->__('Transform a string to upper case', 'function-fields'),
            'lowerCase' => $this->__('Transform a string to lower case', 'function-fields'),
            'titleCase' => $this->__('Transform a string to title case', 'function-fields'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'concat' => [
                'values' => $this->getStringScalarTypeResolver(),
            ],
            'divide' => [
                'number' => $this->getFloatScalarTypeResolver(),
                'by' => $this->getFloatScalarTypeResolver(),
            ],
            'arrayRandom' => [
                'array' => $this->getDangerouslyDynamicScalarTypeResolver(),
            ],
            'arrayJoin' => [
                'array' => $this->getStringScalarTypeResolver(),
                'separator' => $this->getStringScalarTypeResolver(),
            ],
            'arrayItem' => [
                'array' => $this->getDangerouslyDynamicScalarTypeResolver(),
                'position' => $this->getStringScalarTypeResolver(),
            ],
            'arraySearch' => [
                'array' => $this->getDangerouslyDynamicScalarTypeResolver(),
                'element' => $this->getStringScalarTypeResolver(),
            ],
            'arrayFill' => [
                'target' => $this->getDangerouslyDynamicScalarTypeResolver(),
                'source' => $this->getDangerouslyDynamicScalarTypeResolver(),
                'index' => $this->getStringScalarTypeResolver(),
                'properties' => $this->getStringScalarTypeResolver(),
            ],
            'arrayValues' => [
                'array' => $this->getDangerouslyDynamicScalarTypeResolver(),
            ],
            'arrayUnique' => [
                'array' => $this->getDangerouslyDynamicScalarTypeResolver(),
            ],
            'arrayDiff' => [
                'arrays' => $this->getDangerouslyDynamicScalarTypeResolver(),
            ],
            'arrayAddItem' => [
                'array' => $this->getDangerouslyDynamicScalarTypeResolver(),
                'value' => $this->getDangerouslyDynamicScalarTypeResolver(),
                'key' => $this->getArrayKeyScalarTypeResolver(),
            ],
            'arrayAsQueryStr' => [
                'array' => $this->getDangerouslyDynamicScalarTypeResolver(),
            ],
            'objectAsQueryStr' => [
                'object' => $this->getDangerouslyDynamicScalarTypeResolver(),
            ],
            'upperCase',
            'lowerCase',
            'titleCase' => [
                'text' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['concat' => 'values'] => $this->__('Strings to concatenate', 'function-fields'),
            ['divide' => 'number'] => $this->__('Number to divide', 'function-fields'),
            ['divide' => 'by'] => $this->__('The division operandum', 'function-fields'),
            ['arrayRandom' => 'array'] => $this->__('Array of elements from which to randomly select one', 'function-fields'),
            ['arrayJoin' => 'array'] => $this->__('Array of strings to be joined all together', 'function-fields'),
            ['arrayJoin' => 'separator'] => $this->__('Separator with which to join all strings in the array', 'function-fields'),
            ['arrayItem' => 'array'] => $this->__('Array containing the element to retrieve', 'function-fields'),
            ['arrayItem' => 'position'] => $this->__('Position where the element is placed in the array, starting from 0', 'function-fields'),
            ['arraySearch' => 'array'] => $this->__('Array containing the element to search', 'function-fields'),
            ['arraySearch' => 'element'] => $this->__('Element to search in the array and retrieve its position', 'function-fields'),
            ['arrayFill' => 'target'] => $this->__('Array to be added elements coming from the source array', 'function-fields'),
            ['arrayFill' => 'source'] => $this->__('Array whose elements will be added to the target array', 'function-fields'),
            ['arrayFill' => 'index'] => $this->__('Property whose value must be the same on both arrays', 'function-fields'),
            ['arrayFill' => 'properties'] => $this->__('Properties to copy from the source to the target array. If empty, all properties in the source array will be copied', 'function-fields'),
            ['arrayValues' => 'array'] => $this->__('The array from which to retrieve the values', 'function-fields'),
            ['arrayUnique' => 'array'] => $this->__('The array to operate on', 'function-fields'),
            ['arrayDiff' => 'arrays'] => $this->__('The array containing all the arrays. It must have at least 2 elements', 'function-fields'),
            ['arrayAddItem' => 'array'] => $this->__('The array to add an item on', 'function-fields'),
            ['arrayAddItem' => 'value'] => $this->__('The value to add to the array', 'function-fields'),
            ['arrayAddItem' => 'key'] => $this->__('Key (string or integer) under which to add the value to the array. If not provided, the value is added without key', 'function-fields'),
            ['arrayAsQueryStr' => 'array'] => $this->__('The array to be represented as a string', 'function-fields'),
            ['objectAsQueryStr' => 'object'] => $this->__('The object to be represented as a string', 'function-fields'),
            ['upperCase' => 'text'],
            ['lowerCase' => 'text'],
            ['titleCase' => 'text'] => $this->__('The string to be transformed', 'function-fields'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['concat' => 'values'],
            ['arrayRandom' => 'array'],
            ['arrayJoin' => 'array'],
            ['arrayItem' => 'array'],
            ['arraySearch' => 'array'],
            ['arrayFill' => 'target'],
            ['arrayFill' => 'source'],
            ['arrayValues' => 'array'],
            ['arrayUnique' => 'array'],
            ['arrayDiff' => 'arrays'],
            ['arrayAddItem' => 'array'],
            ['arrayAsQueryStr' => 'array']
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            ['divide' => 'number'],
            ['divide' => 'by'],
            ['arrayItem' => 'position'],
            ['arraySearch' => 'element'],
            ['arrayFill' => 'index'],
            ['arrayAddItem' => 'value'],
            ['objectAsQueryStr' => 'object'],
            ['upperCase' => 'text'],
            ['lowerCase' => 'text'],
            ['titleCase' => 'text']
                => SchemaTypeModifiers::MANDATORY,
            ['arrayFill' => 'properties']
                => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    protected function doResolveSchemaValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs
    ): array {
        // Important: The validations below can only be done if no fieldArg contains a field!
        // That is because this is a schema error, so we still don't have the $object against which to resolve the field
        // For instance, this doesn't work: /?query=arrayItem(posts(),3)
        // In that case, the validation will be done inside ->resolveValue(), and will be treated as a $dbError, not a $schemaError
        if (!FieldQueryUtils::isAnyFieldArgumentValueAField($fieldArgs)) {
            switch ($fieldName) {
                case 'arrayItem':
                    if (count($fieldArgs['array']) < $fieldArgs['position']) {
                        return [
                            sprintf(
                                $this->__('The array contains no element at position \'%s\'', 'function-fields'),
                                $fieldArgs['position']
                            ),
                        ];
                    };
                    break;
                case 'arrayDiff':
                    if (count($fieldArgs['arrays']) < 2) {
                        return [
                            sprintf(
                                $this->__('The array must contain at least 2 elements: \'%s\'', 'function-fields'),
                                json_encode($fieldArgs['arrays'])
                            ),
                        ];
                    };
                    break;
                case 'divide':
                    if ($fieldArgs['by'] === (float)0) {
                        return [
                            $this->__('Cannot divide by 0', 'function-fields'),
                        ];
                    }
                    // Check that all items are arrays
                    // This doesn't work before resolving the args! So doing arrayDiff([echo($langs),[en]]) fails
                    // $allArrays = array_reduce($fieldArgs['arrays'], function($carry, $item) {
                    //     return $carry && is_array($item);
                    // }, true);
                    // if (!$allArrays) {
                    //     return sprintf(
                    //         $this->__('The array must contain only arrays as elements: \'%s\'', 'function-fields'),
                    //         json_encode($fieldArgs['arrays'])
                    //     );
                    // }
                    break;
            }
        }

        return parent::doResolveSchemaValidationErrorDescriptions($objectTypeResolver, $fieldName, $fieldArgs);
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
            case 'concat':
                return array_reduce(
                    $fieldArgs['values'],
                    function ($carry, $item) {
                        return $carry . $item;
                    },
                    ''
                );
            case 'divide':
                return (float)$fieldArgs['number'] / (float)$fieldArgs['by'];
            case 'arrayRandom':
                return $fieldArgs['array'][array_rand($fieldArgs['array'])];
            case 'arrayJoin':
                return implode($fieldArgs['separator'] ?? '', $fieldArgs['array']);
            case 'arrayItem':
                return $fieldArgs['array'][$fieldArgs['position']];
            case 'arraySearch':
                return array_search($fieldArgs['element'], $fieldArgs['array']);
            case 'arrayFill':
                // For each element in the source, iterate all the elements in the target
                // If the value for the index property is the same, then copy the properties
                // Cast from stdClass to array
                $value = $fieldArgs['target'];
                $source = $fieldArgs['source'];
                $index = $fieldArgs['index'];
                foreach ($value as &$targetProps) {
                    if (!is_array($targetProps) || !array_key_exists($index, $targetProps)) {
                        continue;
                    }
                    foreach ($source as $sourceProps) {
                        if ($targetProps[$index] != $sourceProps[$index]) {
                            continue;
                        }
                        $properties = isset($fieldArgs['properties']) ? $fieldArgs['properties'] : array_keys($sourceProps);
                        foreach ($properties as $property) {
                            $targetProps[$property] = $sourceProps[$property];
                        }
                    }
                }
                return $value;
            case 'arrayValues':
                return array_values($fieldArgs['array']);
            case 'arrayUnique':
                return array_unique($fieldArgs['array']);
            case 'arrayDiff':
                // Diff the first array against all the others
                $arrays = $fieldArgs['arrays'];
                $first = (array) array_shift($arrays);
                return array_diff($first, ...$arrays);
            case 'arrayAddItem':
                $array = $fieldArgs['array'];
                if ($fieldArgs['key'] ?? null) {
                    $array[$fieldArgs['key']] = $fieldArgs['value'];
                } else {
                    $array[] = $fieldArgs['value'];
                }
                return $array;
            case 'arrayAsQueryStr':
                return $this->getFieldQueryInterpreter()->getArrayAsStringForQuery($fieldArgs['array']);
            case 'objectAsQueryStr':
                return $this->getFieldQueryInterpreter()->getObjectAsStringForQuery($fieldArgs['object']);
            case 'upperCase':
                return strtoupper($fieldArgs['text']);
            case 'lowerCase':
                return strtolower($fieldArgs['text']);
            case 'titleCase':
                return ucwords($fieldArgs['text']);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
