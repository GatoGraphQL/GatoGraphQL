<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\FieldQuery\FieldQueryInterpreter as UpstreamFieldQueryInterpreter;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class FieldQueryInterpreter extends UpstreamFieldQueryInterpreter implements FieldQueryInterpreterInterface
{
    /**
     * @var array<string, array<string, array<string, InputTypeResolverInterface>>>
     */
    private array $directiveArgumentNameTypeResolversCache = [];
    /**
     * @var array<string, array>
     */
    private array $directiveArgumentNameDefaultValuesCache = [];
    /**
     * @var array<string, array|null>
     */
    private array $fieldSchemaDefinitionArgsCache = [];
    /**
     * @var array<string, array>
     */
    private array $directiveSchemaDefinitionArgsCache = [];

    /**
     * @return array<string, InputTypeResolverInterface>
     */
    protected function getDirectiveArgumentNameTypeResolvers(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $relationalTypeResolverClass = get_class($relationalTypeResolver);
        $directiveResolverClass = get_class($directiveResolver);
        if (!isset($this->directiveArgumentNameTypeResolversCache[$directiveResolverClass][$relationalTypeResolverClass])) {
            $this->directiveArgumentNameTypeResolversCache[$directiveResolverClass][$relationalTypeResolverClass] = $this->doGetDirectiveArgumentNameTypeResolvers($directiveResolver, $relationalTypeResolver);
        }
        return $this->directiveArgumentNameTypeResolversCache[$directiveResolverClass][$relationalTypeResolverClass];
    }

    /**
     * @return array<string, InputTypeResolverInterface>
     */
    protected function doGetDirectiveArgumentNameTypeResolvers(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        // Get the fieldDirective argument types, to know to what type it will cast the value
        $directiveArgNameTypes = [];
        if ($directiveSchemaDefinitionArgs = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $relationalTypeResolver)) {
            foreach ($directiveSchemaDefinitionArgs as $directiveSchemaDefinitionArg) {
                $directiveArgNameTypes[$directiveSchemaDefinitionArg[SchemaDefinition::NAME]] = $directiveSchemaDefinitionArg[SchemaDefinition::TYPE_RESOLVER];
            }
        }
        return $directiveArgNameTypes;
    }

    protected function getDirectiveArgumentNameDefaultValues(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $relationalTypeResolverClass = get_class($relationalTypeResolver);
        $directiveResolverClass = get_class($directiveResolver);
        if (!isset($this->directiveArgumentNameDefaultValuesCache[$directiveResolverClass][$relationalTypeResolverClass])) {
            $this->directiveArgumentNameDefaultValuesCache[$directiveResolverClass][$relationalTypeResolverClass] = $this->doGetDirectiveArgumentNameDefaultValues($directiveResolver, $relationalTypeResolver);
        }
        return $this->directiveArgumentNameDefaultValuesCache[$directiveResolverClass][$relationalTypeResolverClass];
    }

    protected function doGetDirectiveArgumentNameDefaultValues(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        // Get the directive arguments which have a default value
        // Set the missing InputObject as {} to give it a chance to set its default input field values
        $directiveArgNameDefaultValues = [];
        if ($directiveSchemaDefinitionArgs = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $relationalTypeResolver)) {
            foreach ($directiveSchemaDefinitionArgs as $directiveSchemaDefinitionArg) {
                if (\array_key_exists(SchemaDefinition::DEFAULT_VALUE, $directiveSchemaDefinitionArg)) {
                    // If it has a default value, set it
                    $directiveArgNameDefaultValues[$directiveSchemaDefinitionArg[SchemaDefinition::NAME]] = $directiveSchemaDefinitionArg[SchemaDefinition::DEFAULT_VALUE];
                } elseif (
                    // If it is a non-mandatory InputObject, set {}
                    // (If it is mandatory, don't set a value as to let the validation fail)
                    $directiveSchemaDefinitionArg[SchemaDefinition::TYPE_RESOLVER] instanceof InputObjectTypeResolverInterface
                    && !($directiveSchemaDefinitionArg[SchemaDefinition::MANDATORY] ?? false)
                ) {
                    $directiveArgNameDefaultValues[$directiveSchemaDefinitionArg[SchemaDefinition::NAME]] = new stdClass();
                }
            }
        }
        return $directiveArgNameDefaultValues;
    }

    protected function getFieldArgsSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, FieldInterface $field): ?array
    {
        $objectTypeResolverClass = get_class($objectTypeResolver);
        if (!array_key_exists($field->asFieldOutputQueryString(), $this->fieldSchemaDefinitionArgsCache[$objectTypeResolverClass] ?? [])) {
            $fieldArgsSchemaDefinition = null;
            $fieldSchemaDefinition = $objectTypeResolver->getFieldSchemaDefinition($field);
            if ($fieldSchemaDefinition !== null) {
                $fieldArgsSchemaDefinition = $fieldSchemaDefinition[SchemaDefinition::ARGS] ?? [];
            }
            $this->fieldSchemaDefinitionArgsCache[$objectTypeResolverClass][$field->asFieldOutputQueryString()] = $fieldArgsSchemaDefinition;
        }
        return $this->fieldSchemaDefinitionArgsCache[$objectTypeResolverClass][$field->asFieldOutputQueryString()];
    }

    protected function getDirectiveSchemaDefinitionArgs(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $relationalTypeResolverClass = get_class($relationalTypeResolver);
        $directiveResolverClass = get_class($directiveResolver);
        if (!isset($this->directiveSchemaDefinitionArgsCache[$directiveResolverClass][$relationalTypeResolverClass])) {
            $directiveSchemaDefinition = $directiveResolver->getDirectiveSchemaDefinition($relationalTypeResolver);
            $directiveSchemaDefinitionArgs = $directiveSchemaDefinition[SchemaDefinition::ARGS] ?? [];
            $this->directiveSchemaDefinitionArgsCache[$directiveResolverClass][$relationalTypeResolverClass] = $directiveSchemaDefinitionArgs;
        }
        return $this->directiveSchemaDefinitionArgsCache[$directiveResolverClass][$relationalTypeResolverClass];
    }

    public function resolveExpression(
        RelationalTypeResolverInterface $relationalTypeResolver,
        mixed $fieldArgValue,
        ?array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // Expressions: allow to pass a field argument "key:%input%", which is passed when executing the directive through $expressions

        /**
         * Switched from "%{...}%" to "$__..."
         * @todo Convert expressions from "$__" to "$"
         */
        // // Trim it so that "%{ self }%" is equivalent to "%{self}%". This is needed to set expressions through Symfony's DependencyInjection component (since %...% is reserved for its own parameters!)
        // $expressionName = trim(substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING), strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING) - strlen(QuerySyntax::SYMBOL_EXPRESSION_CLOSING)));
        $expressionName = substr($fieldArgValue, strlen('$__'));
        if (!isset($expressions[$expressionName])) {
            // If the expression is not set, then show the error under entry "expressionErrors"
            // @todo Temporarily hack fix: Need to pass astNode but don't have it, so commented
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             ErrorFeedbackItemProvider::class,
            //             ErrorFeedbackItemProvider::E14,
            //             [
            //                 $expressionName,
            //             ]
            //         ),
            //         LocationHelper::getNonSpecificLocation(),
            //         $relationalTypeResolver,
            //     )
            // );
            return null;
        }
        return $expressions[$expressionName];
    }
}
