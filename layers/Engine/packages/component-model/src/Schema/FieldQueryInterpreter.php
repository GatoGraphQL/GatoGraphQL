<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\FieldQuery\FieldQueryInterpreter as UpstreamFieldQueryInterpreter;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryUtils;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\FeedbackItemProviders\GenericFeedbackItemProvider;
use SplObjectStorage;
use stdClass;

class FieldQueryInterpreter extends UpstreamFieldQueryInterpreter implements FieldQueryInterpreterInterface
{
    /**
     * @var array<string, array>
     */
    private array $extractedFieldArgumentsCache = [];
    /**
     * @var array<string, array>
     */
    private array $extractedDirectiveArgumentsCache = [];
    /**
     * @var array<string, array<string, array<string, InputTypeResolverInterface>|null>>
     */
    private array $fieldArgumentNameTypeResolversCache = [];
    /**
     * @var array<string, array<string, array<string, InputTypeResolverInterface>>>
     */
    private array $directiveArgumentNameTypeResolversCache = [];
    /**
     * @var array<string, array>
     */
    private array $fieldArgumentNameDefaultValuesCache = [];
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

    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?InputCoercingServiceInterface $inputCoercingService = null;
    private ?ObjectSerializationManagerInterface $objectSerializationManager = null;

    final public function setDangerouslyNonSpecificScalarTypeScalarTypeResolver(DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver): void
    {
        $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
    }
    final public function setInputCoercingService(InputCoercingServiceInterface $inputCoercingService): void
    {
        $this->inputCoercingService = $inputCoercingService;
    }
    final protected function getInputCoercingService(): InputCoercingServiceInterface
    {
        return $this->inputCoercingService ??= $this->instanceManager->getInstance(InputCoercingServiceInterface::class);
    }
    final public function setObjectSerializationManager(ObjectSerializationManagerInterface $objectSerializationManager): void
    {
        $this->objectSerializationManager = $objectSerializationManager;
    }
    final protected function getObjectSerializationManager(): ObjectSerializationManagerInterface
    {
        return $this->objectSerializationManager ??= $this->instanceManager->getInstance(ObjectSerializationManagerInterface::class);
    }

    protected function getVariablesHash(?array $variables): string
    {
        return (string)hash('crc32', json_encode($variables ?? []));
    }

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

    protected function isFieldArgumentValueWrappedWithStringSymbols(string $fieldArgValue): bool
    {
        return
            substr($fieldArgValue, 0, strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING)) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING &&
            substr($fieldArgValue, -1 * strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING)) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING;
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

    protected function serializeObject(object $object): string
    {
        if ($object instanceof WithValueInterface) {
            return $object->getValue();
        }
        return $this->getObjectSerializationManager()->serialize($object);
    }
}
