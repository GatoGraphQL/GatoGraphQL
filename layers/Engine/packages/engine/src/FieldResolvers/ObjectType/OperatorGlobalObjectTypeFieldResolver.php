<?php

declare(strict_types=1);

namespace PoP\Engine\FieldResolvers\ObjectType;

use ArgumentCountError;
use Exception;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractGlobalObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;
use PoP\Engine\Misc\OperatorHelpers;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class OperatorGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    public const HOOK_SAFEVARS = __CLASS__ . ':safeVars';

    /**
     * @var array<string, mixed>
     */
    protected ?array $safeVars = null;
    protected DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver;
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    protected JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected ErrorProviderInterface $errorProvider;

    #[Required]
    final public function autowireOperatorGlobalObjectTypeFieldResolver(
        DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver,
        IntScalarTypeResolver $intScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        ErrorProviderInterface $errorProvider,
    ): void {
        $this->dangerouslyDynamicScalarTypeResolver = $dangerouslyDynamicScalarTypeResolver;
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->errorProvider = $errorProvider;
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
            'var',
            'context',
            'extract',
            'time',
            'echo',
            'sprintf',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'if' => $this->dangerouslyDynamicScalarTypeResolver,
            'not' => $this->booleanScalarTypeResolver,
            'and' => $this->booleanScalarTypeResolver,
            'or' => $this->booleanScalarTypeResolver,
            'equals' => $this->booleanScalarTypeResolver,
            'empty' => $this->booleanScalarTypeResolver,
            'isNull' => $this->booleanScalarTypeResolver,
            'var' => $this->dangerouslyDynamicScalarTypeResolver,
            'context' => $this->jsonObjectScalarTypeResolver,
            'extract' => $this->dangerouslyDynamicScalarTypeResolver,
            'time' => $this->intScalarTypeResolver,
            'echo' => $this->dangerouslyDynamicScalarTypeResolver,
            'sprintf' => $this->stringScalarTypeResolver,
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
            'context',
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
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'if' => [
                'condition' => $this->booleanScalarTypeResolver,
                'then' => $this->dangerouslyDynamicScalarTypeResolver,
                'else' => $this->dangerouslyDynamicScalarTypeResolver,
            ],
            'not' => [
                'value' => $this->booleanScalarTypeResolver,
            ],
            'and',
            'or' => [
                'values' => $this->booleanScalarTypeResolver,
            ],
            'equals' => [
                'value1' => $this->dangerouslyDynamicScalarTypeResolver,
                'value2' => $this->dangerouslyDynamicScalarTypeResolver,
            ],
            'empty' => [
                'value' => $this->dangerouslyDynamicScalarTypeResolver,
            ],
            'isNull' => [
                'value' => $this->dangerouslyDynamicScalarTypeResolver,
            ],
            'var' => [
                'name' => $this->stringScalarTypeResolver,
            ],
            'extract' => [
                'object' => $this->dangerouslyDynamicScalarTypeResolver,
                'path' => $this->stringScalarTypeResolver,
            ],
            'echo' => [
                'value' => $this->dangerouslyDynamicScalarTypeResolver,
            ],
            'sprintf' => [
                'string' => $this->stringScalarTypeResolver,
                'values' => $this->stringScalarTypeResolver,
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['if' => 'condition'] => $this->translationAPI->__('The condition to check if its value is `true` or `false`', 'component-model'),
            ['if' => 'then'] => $this->translationAPI->__('The value to return if the condition evals to `true`', 'component-model'),
            ['if' => 'else'] => $this->translationAPI->__('The value to return if the condition evals to `false`', 'component-model'),
            ['not' => 'value'] => $this->translationAPI->__('The value from which to return its opposite value', 'component-model'),
            ['and' => 'values'],
            ['or' => 'values'] => sprintf(
                $this->translationAPI->__('The array of values on which to execute the `%s` operation', 'component-model'),
                strtoupper($fieldName)
            ),
            ['equals' => 'value1'] => $this->translationAPI->__('The first value to compare', 'component-model'),
            ['equals' => 'value2'] => $this->translationAPI->__('The second value to compare', 'component-model'),
            ['empty' => 'value'] => $this->translationAPI->__('The value to check if it is empty', 'component-model'),
            ['isNull' => 'value'] => $this->translationAPI->__('The value to check if it is null', 'component-model'),
            ['var' => 'name'] => $this->translationAPI->__('The name of the variable to retrieve from the `$vars` context object', 'component-model'),
            ['extract' => 'object'] => $this->translationAPI->__('The object to retrieve the data from', 'pop-component-model'),
            ['extract' => 'path'] => $this->translationAPI->__('The path to retrieve data from the object. Paths are separated with \'.\' for each sublevel', 'pop-component-model'),
            ['echo' => 'value'] => $this->translationAPI->__('The input to be echoed back', 'function-fields'),
            ['sprintf' => 'string'] => $this->translationAPI->__('The string containing the placeholders', 'function-fields'),
            ['sprintf' => 'values'] => $this->translationAPI->__('The values to replace the placeholders with inside the string', 'function-fields'),
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
            ['var' => 'name'],
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

    protected function doResolveSchemaValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        // Important: The validations below can only be done if no fieldArg contains a field!
        // That is because this is a schema error, so we still don't have the $object against which to resolve the field
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
                    break;
            }
        }

        return parent::doResolveSchemaValidationErrorDescriptions($objectTypeResolver, $fieldName, $fieldArgs);
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
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
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
                try {
                    $array = (array) $fieldArgs['object'];
                    $pointerToArrayItemUnderPath = OperatorHelpers::getPointerToArrayItemUnderPath($array, $fieldArgs['path']);
                } catch (Exception $e) {
                    return $this->errorProvider->getError(
                        $fieldName,
                        'path-not-reachable',
                        $e->getMessage()
                    );
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
                    return new Error(
                        'sprintf-wrong-params',
                        sprintf(
                            $this->translationAPI->__('There was an error executing `sprintf`: %s', 'engine'),
                            $e->getMessage()
                        )
                    );
                }
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
