<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\Dataloading\Expressions;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\FieldQuery\QueryHelpers;

class ApplyFunctionDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    /**
     * "Functions" are global fields, defined in all TypeResolvers.
     * Use RootObjectTypeResolver instead of $relationalTypeResolver
     * since this could be a UnionTypeResolver,
     * but `extractFieldArguments` expects an ObjectTypeResolver
     */
    private ?RootObjectTypeResolver $rootObjectTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setRootObjectTypeResolver(RootObjectTypeResolver $rootObjectTypeResolver): void
    {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
    }
    final protected function getRootObjectTypeResolver(): RootObjectTypeResolver
    {
        return $this->rootObjectTypeResolver ??= $this->instanceManager->getInstance(RootObjectTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getDirectiveName(): string
    {
        return 'applyFunction';
    }

    /**
     * This is a "Scripting" type directive
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::SCRIPTING;
    }

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            'function' => $this->getStringScalarTypeResolver(),
            'addArguments' => $this->getDangerouslyDynamicScalarTypeResolver(),
            'target' => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'function' => $this->__('Function to execute on the affected fields', 'component-model'),
            'addArguments' => sprintf(
                $this->__('Arguments to inject to the function. The value of the affected field can be provided under special expression `%s`', 'component-model'),
                QueryHelpers::getExpressionQuery(Expressions::NAME_VALUE)
            ),
            'target' => $this->__('Property from the current object where to store the results of the function. If the result must not be stored, pass an empty value. Default value: Same property as the affected field', 'component-model'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'function' => SchemaTypeModifiers::MANDATORY,
            'addArguments' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveExpressions(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            Expressions::NAME_VALUE => $this->__('Element being transformed', 'component-model'),
        ];
    }

    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idsDataFields,
        array $succeedingPipelineDirectiveResolverInstances,
        array $objectIDItems,
        array $unionDBKeyIDs,
        array $previousDBItems,
        array &$succeedingPipelineIDsDataFields,
        array &$dbItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        $this->regenerateAndExecuteFunction($relationalTypeResolver, $objectIDItems, $idsDataFields, $dbItems, $previousDBItems, $variables, $messages, $objectErrors, $objectWarnings, $objectDeprecations, $schemaErrors, $schemaWarnings, $schemaDeprecations);
    }

    /**
     * Execute a function on the affected field
     */
    protected function regenerateAndExecuteFunction(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $objectIDItems,
        array $idsDataFields,
        array &$dbItems,
        array $previousDBItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
    ): void {
        $function = $this->directiveArgsForSchema['function'];
        $addArguments = $this->directiveArgsForSchema['addArguments'] ?? [];
        $target = $this->directiveArgsForSchema['target'] ?? null;

        // Maybe re-generate the function: Inject the provided `$addArguments` to the fieldArgs already declared in the query
        if ($addArguments) {
            $functionName = $this->getFieldQueryInterpreter()->getFieldName($function);
            $functionArgElems = array_merge(
                $this->getFieldQueryInterpreter()->extractFieldArguments($this->getRootObjectTypeResolver(), $function) ?? [],
                $addArguments
            );
            $function = $this->getFieldQueryInterpreter()->getField($functionName, $functionArgElems);
        }
        $dbKey = $relationalTypeResolver->getTypeOutputDBKey();

        // Get the value from the object
        foreach ($idsDataFields as $id => $dataFields) {
            $object = $objectIDItems[$id];
            foreach ($dataFields['direct'] as $field) {
                $fieldOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $field, $object);

                // Validate that the property exists
                $isValueInDBItems = array_key_exists($fieldOutputKey, $dbItems[(string)$id] ?? []);
                if (!$isValueInDBItems && !array_key_exists($fieldOutputKey, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                    if ($fieldOutputKey != $field) {
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->__('Field \'%s\' (under property \'%s\') hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'),
                                $field,
                                $fieldOutputKey,
                                $id
                            ),
                        ];
                    } else {
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'),
                                $fieldOutputKey,
                                $id
                            ),
                        ];
                    }
                    continue;
                }

                // Place all the reserved expressions into the `$expressions` context: $value
                $this->addExpressionsForObject($relationalTypeResolver, $id, $field, $objectIDItems, $dbItems, $previousDBItems, $variables, $messages, $objectErrors, $objectWarnings, $objectDeprecations, $schemaErrors, $schemaWarnings, $schemaDeprecations);

                // Generate the fieldArgs from combining the query with the values in the context, through $variables
                $expressions = $this->getExpressionsForObject($id, $variables, $messages);
                list(
                    $validFunction,
                    $schemaFieldName,
                    $schemaFieldArgs,
                    $schemaObjectErrors,
                    $schemaObjectWarnings
                ) = $this->getFieldQueryInterpreter()->extractFieldArgumentsForSchema($this->getRootObjectTypeResolver(), $function, $variables);

                // Place the errors not under schema but under DB, since they may change on a object by object basis
                if ($schemaObjectWarnings) {
                    $dbWarning = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $this->__('Nested warnings validating function \'%s\' on object with ID \'%s\' and field under property \'%s\')', 'component-model'),
                            $function,
                            $id,
                            $fieldOutputKey
                        )
                    ];
                    foreach ($schemaObjectWarnings as $schemaDBWarning) {
                        array_unshift($schemaDBWarning[Tokens::PATH], $this->directive);
                        $this->prependPathOnNestedErrors($schemaDBWarning);
                        $dbWarning[Tokens::EXTENSIONS][Tokens::NESTED][] = $schemaDBWarning;
                    }
                    $objectWarnings[(string)$id][] = $dbWarning;
                }
                if ($schemaObjectErrors) {
                    if ($fieldOutputKey != $field) {
                        $errorMessage = sprintf(
                            $this->__('Applying function on field \'%s\' (under property \'%s\') on object with ID \'%s\' can\'t be executed due to nested errors', 'component-model'),
                            $field,
                            $fieldOutputKey,
                            $id
                        );
                    } else {
                        $errorMessage = sprintf(
                            $this->__('Applying function on field \'%s\' on object with ID \'%s\' can\'t be executed due to nested errors', 'component-model'),
                            $fieldOutputKey,
                            $id
                        );
                    }
                    $dbError = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => $errorMessage
                    ];
                    foreach ($schemaObjectErrors as $schemaDBError) {
                        array_unshift($schemaDBError[Tokens::PATH], $this->directive);
                        $this->prependPathOnNestedErrors($schemaDBError);
                        $dbError[Tokens::EXTENSIONS][Tokens::NESTED][] = $schemaDBError;
                    }
                    $objectErrors[(string)$id][] = $dbError;
                    continue;
                }

                // Execute the function
                // Because the function was dynamically created, we must indicate to validate the schema when doing ->resolveValue
                $options = [
                    AbstractRelationalTypeResolver::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM => true,
                ];
                $functionValue = $relationalTypeResolver->resolveValue($objectIDItems[(string)$id], $validFunction, $variables, $expressions, $options);
                // Merge the objectWarnings, if any
                if ($storedObjectWarnings = $this->getFeedbackMessageStore()->retrieveAndClearObjectWarnings($id)) {
                    $objectWarnings[$id] = array_merge(
                        $objectWarnings[$id] ?? [],
                        $storedObjectWarnings
                    );
                }

                // If there was an error (eg: a missing mandatory argument), then the function will be of type Error
                if (GeneralUtils::isError($functionValue)) {
                    /** @var Error */
                    $error = $functionValue;
                    $objectErrors[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $this->__('Applying function on \'%s\' on object with ID \'%s\' failed due to error: %s', 'component-model'),
                            $fieldOutputKey,
                            $id,
                            $error->getMessageOrCode()
                        ),
                    ];
                    continue;
                }

                // Store the results:
                // If there is a target specified, use it
                // If the specified target is empty, then do not store the results
                // If no target was specified, use the same affected field
                $functionTarget = $target ?? $fieldOutputKey;
                if ($functionTarget) {
                    $dbItems[(string)$id][$functionTarget] = $functionValue;
                }
            }
        }
    }

    /**
     * Place all the reserved variables into the `$variables` context
     */
    protected function addExpressionsForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        string $field,
        array $objectIDItems,
        array &$dbItems,
        array $previousDBItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations
    ): void {
        $object = $objectIDItems[$id];
        $fieldOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $field, $object);
        $isValueInDBItems = array_key_exists($fieldOutputKey, $dbItems[(string)$id] ?? []);
        $dbKey = $relationalTypeResolver->getTypeOutputDBKey();
        $value = $isValueInDBItems ?
            $dbItems[(string)$id][$fieldOutputKey] :
            $previousDBItems[$dbKey][(string)$id][$fieldOutputKey];
        $this->addExpressionForObject($id, Expressions::NAME_VALUE, $value, $messages);
    }
}
