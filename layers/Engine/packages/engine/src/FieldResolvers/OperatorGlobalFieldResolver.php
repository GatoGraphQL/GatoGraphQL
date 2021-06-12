<?php

declare(strict_types=1);

namespace PoP\Engine\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractGlobalFieldResolver;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\Misc\Extract;

class OperatorGlobalFieldResolver extends AbstractGlobalFieldResolver
{
    /**
     * @var array<string, mixed>
     */
    protected ?array $safeVars = null;

    public const HOOK_SAFEVARS = __CLASS__ . ':safeVars';
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
            'var',
            'context',
            'extract',
            'time',
            'echo',
            'sprintf',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'if' => SchemaDefinition::TYPE_MIXED,
            'not' => SchemaDefinition::TYPE_BOOL,
            'and' => SchemaDefinition::TYPE_BOOL,
            'or' => SchemaDefinition::TYPE_BOOL,
            'equals' => SchemaDefinition::TYPE_BOOL,
            'empty' => SchemaDefinition::TYPE_BOOL,
            'isNull' => SchemaDefinition::TYPE_BOOL,
            'var' => SchemaDefinition::TYPE_MIXED,
            'context' => SchemaDefinition::TYPE_OBJECT,
            'extract' => SchemaDefinition::TYPE_MIXED,
            'time' => SchemaDefinition::TYPE_INT,
            'echo' => SchemaDefinition::TYPE_MIXED,
            'sprintf' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        switch ($fieldName) {
            case 'not':
            case 'and':
            case 'or':
            case 'equals':
            case 'empty':
            case 'isNull':
            case 'context':
            case 'time':
            case 'sprintf':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'if' => $this->translationAPI->__('If a boolean property is true, execute a field, else, execute another field', 'component-model'),
            'not' => $this->translationAPI->__('Return the opposite value of a boolean property', 'component-model'),
            'and' => $this->translationAPI->__('Return an `AND` operation among several boolean properties', 'component-model'),
            'or' => $this->translationAPI->__('Return an `OR` operation among several boolean properties', 'component-model'),
            'equals' => $this->translationAPI->__('Indicate if the result from a field equals a certain value', 'component-model'),
            'empty' => $this->translationAPI->__('Indicate if a value is empty', 'component-model'),
            'isNull' => $this->translationAPI->__('Indicate if a value is null', 'component-model'),
            'var' => $this->translationAPI->__('Retrieve the value of a certain property from the `$vars` context object', 'component-model'),
            'context' => $this->translationAPI->__('Retrieve the `$vars` context object', 'component-model'),
            'extract' => $this->translationAPI->__('Given an object, it retrieves the data under a certain path', 'pop-component-model'),
            'time' => $this->translationAPI->__('Return the time now (https://php.net/manual/en/function.time.php)', 'component-model'),
            'echo' => $this->translationAPI->__('Repeat back the input, whatever it is', 'function-fields'),
            'sprintf' => $this->translationAPI->__('Replace placeholders inside a string with provided values', 'function-fields'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'if':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'condition',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The condition to check if its value is `true` or `false`', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'then',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The value to return if the condition evals to `true`', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'else',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The value to return if the condition evals to `false`', 'component-model'),
                        ],
                    ]
                );

            case 'not':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'value',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The value from which to return its opposite value', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'and':
            case 'or':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'values',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                            SchemaDefinition::ARGNAME_IS_ARRAY => true,
                            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                                $this->translationAPI->__('The array of values on which to execute the `%s` operation', 'component-model'),
                                strtoupper($fieldName)
                            ),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'equals':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'value1',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The first value to compare', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'value2',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The second value to compare', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'empty':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'value',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The value to check if it is empty', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'isNull':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'value',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The value to check if it is null', 'component-model'),
                        ],
                    ]
                );

            case 'var':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'name',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The name of the variable to retrieve from the `$vars` context object', 'component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'extract':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'object',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_OBJECT,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The object to retrieve the data from', 'pop-component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'path',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The path to retrieve data from the object. Paths are separated with \'.\' for each sublevel', 'pop-component-model'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'echo':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'value',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The input to be echoed back', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'sprintf':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'string',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The string containing the placeholders', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'values',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_IS_ARRAY => true,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The values to replace the placeholders with inside the string', 'function-fields'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    public function resolveSchemaValidationErrorDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        if ($errors = parent::resolveSchemaValidationErrorDescriptions($typeResolver, $fieldName, $fieldArgs)) {
            return $errors;
        }

        // Important: The validations below can only be done if no fieldArg contains a field!
        // That is because this is a schema error, so we still don't have the $resultItem against which to resolve the field
        // For instance, this doesn't work: /?query=arrayItem(posts(),3)
        // In that case, the validation will be done inside ->resolveValue(), and will be treated as a $dbError, not a $schemaError
        if (!FieldQueryUtils::isAnyFieldArgumentValueAField($fieldArgs)) {
            switch ($fieldName) {
                case 'var':
                    $safeVars = $this->getSafeVars();
                    if (!isset($safeVars[$fieldArgs['name']])) {
                        return [
                            sprintf(
                                $this->translationAPI->__('Var \'%s\' does not exist in `$vars`', 'component-model'),
                                $fieldArgs['name']
                            )
                        ];
                    };
                    return null;
            }
        }

        return null;
    }

    protected function getSafeVars()
    {
        if (is_null($this->safeVars)) {
            $this->safeVars = ApplicationState::getVars();
            $this->hooksAPI->doAction(
                self::HOOK_SAFEVARS,
                array(&$this->safeVars)
            );
        }
        return $this->safeVars;
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
            case 'var':
                $safeVars = $this->getSafeVars();
                return $safeVars[$fieldArgs['name']];
            case 'context':
                return $this->getSafeVars();
            case 'extract':
                return Extract::getDataFromPath($fieldName, $fieldArgs['object'], $fieldArgs['path']);
            case 'time':
                return time();
            case 'echo':
                return $fieldArgs['value'];
            case 'sprintf':
                return sprintf($fieldArgs['string'], ...$fieldArgs['values']);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
