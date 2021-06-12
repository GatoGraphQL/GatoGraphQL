<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use Composer\Semver\Semver;
use Exception;
use League\Pipeline\StageInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\FieldSymbols;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Versioning\VersioningHelpers;
use PoP\FieldQuery\QueryHelpers;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractDirectiveResolver implements DirectiveResolverInterface, SchemaDirectiveResolverInterface, StageInterface
{
    use AttachableExtensionTrait;
    use RemoveIDsDataFieldsDirectiveResolverTrait;
    use FieldOrDirectiveResolverTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    const MESSAGE_EXPRESSIONS = 'expressions';

    protected string $directive;
    protected TranslationAPIInterface $translationAPI;
    protected HooksAPIInterface $hooksAPI;
    protected InstanceManagerInterface $instanceManager;
    protected FieldQueryInterpreterInterface $fieldQueryInterpreter;
    protected FeedbackMessageStoreInterface $feedbackMessageStore;
    /**
     * @var array<string, mixed>
     */
    protected array $directiveArgsForSchema = [];
    /**
     * @var array<string, mixed>
     */
    protected array $directiveArgsForResultItems = [];
    /**
     * @var array[]
     */
    protected array $nestedDirectivePipelineData = [];
    
    /**
     * The directiveResolvers are NOT instantiated through the service container!
     * Instead, the directive will be instantiated in AbstractTypeResolver:
     *   new $directiveClass($fieldDirective)
     * Then, the constructor is made final, to avoid creating inheriting classes
     * whose properties are expected to be injected via dependency injection.
     *
     * Whenever having depended-upon services,
     * these must be obtained from the container by doing:
     *   $instanceManager->getInstance(...)
     *
     * DirectiveResolvers must still be added to schema-services.yml, though.
     * This is because they need to be registered, so that all directives
     * can be displayed in the GraphQL API's Access Control Lists
     */
    final public function __construct(?string $directive = null)
    {
        // If the directive is not provided, then it directly the directive name
        // This allows to instantiate the directive through the DependencyInjection component
        $this->directive = $directive ?? $this->getDirectiveName();
        // Obtain these services directly from the container, instead of using autowiring
        $this->translationAPI = TranslationAPIFacade::getInstance();
        $this->hooksAPI = HooksAPIFacade::getInstance();
        $this->instanceManager = InstanceManagerFacade::getInstance();
        $this->fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $this->feedbackMessageStore = FeedbackMessageStoreFacade::getInstance();
    }

    /**
     * Directives can be either of type "Schema" or "Query" and,
     * depending on one case or the other, might be exposed to the user.
     * By default, use the Query type
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::QUERY;
    }

    /**
     * If a directive does not operate over the resultItems, then it must not allow to add fields or dynamic values in the directive arguments
     * Otherwise, it can lead to errors, since the field would never be transformed/casted to the expected type
     * Eg: <cacheControl(maxAge:id())>
     */
    protected function disableDynamicFieldsFromDirectiveArgs(): bool
    {
        return false;
    }

    public function dissectAndValidateDirectiveForSchema(
        TypeResolverInterface $typeResolver,
        array &$fieldDirectiveFields,
        array &$variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array {
        // If it has nestedDirectives, extract them and validate them
        $nestedFieldDirectives = $this->fieldQueryInterpreter->getFieldDirectives($this->directive, false);
        if ($nestedFieldDirectives) {
            $nestedDirectiveSchemaErrors = $nestedDirectiveSchemaWarnings = $nestedDirectiveSchemaDeprecations = $nestedDirectiveSchemaNotices = $nestedDirectiveSchemaTraces = [];
            $nestedFieldDirectives = QueryHelpers::splitFieldDirectives($nestedFieldDirectives);
            // Support repeated fields by adding a counter next to them
            if (count($nestedFieldDirectives) != count(array_unique($nestedFieldDirectives))) {
                // Find the repeated fields, and add a counter next to them
                $expandedNestedFieldDirectives = [];
                $counters = [];
                foreach ($nestedFieldDirectives as $nestedFieldDirective) {
                    if (!isset($counters[$nestedFieldDirective])) {
                        $expandedNestedFieldDirectives[] = $nestedFieldDirective;
                        $counters[$nestedFieldDirective] = 1;
                    } else {
                        $expandedNestedFieldDirectives[] = $nestedFieldDirective . FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR . $counters[$nestedFieldDirective];
                        $counters[$nestedFieldDirective]++;
                    }
                }
                $nestedFieldDirectives = $expandedNestedFieldDirectives;
            }
            // Each composed directive will deal with the same fields as the current directive
            $nestedFieldDirectiveFields = $fieldDirectiveFields;
            foreach ($nestedFieldDirectives as $nestedFieldDirective) {
                $nestedFieldDirectiveFields[$nestedFieldDirective] = $fieldDirectiveFields[$this->directive];
            }
            $this->nestedDirectivePipelineData = $typeResolver->resolveDirectivesIntoPipelineData(
                $nestedFieldDirectives,
                $nestedFieldDirectiveFields,
                true,
                $variables,
                $nestedDirectiveSchemaErrors,
                $nestedDirectiveSchemaWarnings,
                $nestedDirectiveSchemaDeprecations,
                $nestedDirectiveSchemaNotices,
                $nestedDirectiveSchemaTraces
            );
            foreach ($nestedDirectiveSchemaDeprecations as $nestedDirectiveSchemaDeprecation) {
                $schemaDeprecations[] = [
                    Tokens::PATH => array_merge([$this->directive], $nestedDirectiveSchemaDeprecation[Tokens::PATH]),
                    Tokens::MESSAGE => $nestedDirectiveSchemaDeprecation[Tokens::MESSAGE],
                ];
            }
            foreach ($nestedDirectiveSchemaWarnings as $nestedDirectiveSchemaWarning) {
                $schemaWarnings[] = [
                    Tokens::PATH => array_merge([$this->directive], $nestedDirectiveSchemaWarning[Tokens::PATH]),
                    Tokens::MESSAGE => $nestedDirectiveSchemaWarning[Tokens::MESSAGE],
                ];
            }
            foreach ($nestedDirectiveSchemaNotices as $nestedDirectiveSchemaNotice) {
                $schemaNotices[] = [
                    Tokens::PATH => array_merge([$this->directive], $nestedDirectiveSchemaNotice[Tokens::PATH]),
                    Tokens::MESSAGE => $nestedDirectiveSchemaNotice[Tokens::MESSAGE],
                ];
            }
            foreach ($nestedDirectiveSchemaTraces as $nestedDirectiveSchemaTrace) {
                $schemaTraces[] = [
                    Tokens::PATH => array_merge([$this->directive], $nestedDirectiveSchemaTrace[Tokens::PATH]),
                    Tokens::MESSAGE => $nestedDirectiveSchemaTrace[Tokens::MESSAGE],
                ];
            }
            // If there is any error, then we also can't proceed with the current directive
            if ($nestedDirectiveSchemaErrors) {
                foreach ($nestedDirectiveSchemaErrors as $nestedDirectiveSchemaError) {
                    $schemaErrors[] = [
                        Tokens::PATH => array_merge([$this->directive], $nestedDirectiveSchemaError[Tokens::PATH]),
                        Tokens::MESSAGE => $nestedDirectiveSchemaError[Tokens::MESSAGE],
                    ];
                }
                $schemaErrors[] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => $this->translationAPI->__('This directive can\'t be executed due to errors from its composed directives', 'component-model'),
                ];
                return [
                    null, // $validDirective
                    null, // $directiveName
                    null, // $directiveArgs
                ];
            }
        }

        // First validate schema (eg of error in schema: ?query=posts<include(if:this-field-doesnt-exist())>)
        list(
            $validDirective,
            $directiveName,
            $directiveArgs,
            $directiveSchemaErrors,
            $directiveSchemaWarnings,
            $directiveSchemaDeprecations
        ) = $this->fieldQueryInterpreter->extractDirectiveArgumentsForSchema(
            $this,
            $typeResolver,
            $this->directive,
            $variables,
            $this->disableDynamicFieldsFromDirectiveArgs()
        );

        // Store the args, they may be used in `resolveDirective`
        $this->directiveArgsForSchema = $directiveArgs;

        // If there were errors, warning or deprecations, integrate them into the feedback objects
        $schemaErrors = array_merge(
            $schemaErrors,
            $directiveSchemaErrors
        );
        // foreach ($directiveSchemaErrors as $directiveSchemaError) {
        //     $schemaErrors[] = [
        //         Tokens::PATH => array_merge([$this->directive], $directiveSchemaError[Tokens::PATH]),
        //         Tokens::MESSAGE => $directiveSchemaError[Tokens::MESSAGE],
        //     ];
        // }
        $schemaWarnings = array_merge(
            $schemaWarnings,
            $directiveSchemaWarnings
        );
        // foreach ($directiveSchemaWarnings as $directiveSchemaWarning) {
        //     $schemaWarnings[] = [
        //         Tokens::PATH => array_merge([$this->directive], $directiveSchemaWarning[Tokens::PATH]),
        //         Tokens::MESSAGE => $directiveSchemaWarning[Tokens::MESSAGE],
        //     ];
        // }
        $schemaDeprecations = array_merge(
            $schemaDeprecations,
            $directiveSchemaDeprecations
        );
        // foreach ($directiveSchemaDeprecations as $directiveSchemaDeprecation) {
        //     $schemaDeprecations[] = [
        //         Tokens::PATH => array_merge([$this->directive], $directiveSchemaDeprecation[Tokens::PATH]),
        //         Tokens::MESSAGE => $directiveSchemaDeprecation[Tokens::MESSAGE],
        //     ];
        // }
        return [
            $validDirective,
            $directiveName,
            $directiveArgs,
        ];
    }

    /**
     * By default, validate if there are deprecated fields
     */
    public function validateDirectiveArgumentsForSchema(TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        if (
            $maybeDeprecation = $this->resolveSchemaDirectiveDeprecationDescription(
                $typeResolver,
                $directiveName,
                $directiveArgs
            )
        ) {
            $schemaDeprecations[] = [
                Tokens::PATH => [$this->directive],
                Tokens::MESSAGE => $maybeDeprecation,
            ];
        }
        return $directiveArgs;
    }

    public function dissectAndValidateDirectiveForResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        array &$variables,
        array &$expressions,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations
    ): array {
        list(
            $validDirective,
            $directiveName,
            $directiveArgs,
            $nestedDBErrors,
            $nestedDBWarnings
        ) = $this->fieldQueryInterpreter->extractDirectiveArgumentsForResultItem($this, $typeResolver, $resultItem, $this->directive, $variables, $expressions);

        // Store the args, they may be used in `resolveDirective`
        $this->directiveArgsForResultItems[$typeResolver->getID($resultItem)] = $directiveArgs;

        if ($nestedDBWarnings || $nestedDBErrors) {
            foreach ($nestedDBErrors as $id => $fieldOutputKeyErrorMessages) {
                $dbErrors[$id] = array_merge(
                    $dbErrors[$id] ?? [],
                    $fieldOutputKeyErrorMessages
                );
            }
            foreach ($nestedDBWarnings as $id => $fieldOutputKeyWarningMessages) {
                $dbWarnings[$id] = array_merge(
                    $dbWarnings[$id] ?? [],
                    $fieldOutputKeyWarningMessages
                );
            }
        }
        return [
            $validDirective,
            $directiveName,
            $directiveArgs,
        ];
    }

    /**
     * Indicate to what fieldNames this directive can be applied.
     * Returning an empty array means all of them
     */
    public function getFieldNamesToApplyTo(): array
    {
        // By default, apply to all fieldNames
        return [];
    }

    /**
     * Define if to use the version to decide if to process the directive or not
     */
    public function decideCanProcessBasedOnVersionConstraint(TypeResolverInterface $typeResolver): bool
    {
        return false;
    }

    /**
     * By default, the directiveResolver instance can process the directive
     * This function can be overriden to force certain value on the directive args before it can be executed
     */
    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs, string $field, array &$variables): bool
    {
        /** Check if to validate the version */
        if (
            Environment::enableSemanticVersionConstraints() &&
            $this->decideCanProcessBasedOnVersionConstraint($typeResolver)
        ) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at the FieldResolver level,
             * and not the FieldInterfaceResolver, which is the other entity filling data
             * inside the schemaDefinition object.
             * If this directive is tagged with a version...
             */
            if ($schemaDirectiveVersion = $this->getSchemaDirectiveVersion($typeResolver)) {
                $vars = ApplicationState::getVars();
                /**
                 * Get versionConstraint in this order:
                 * 1. Passed as directive argument
                 * 2. Through param `directiveVersionConstraints[$directiveName]`: specific to the directive
                 * 3. Through param `versionConstraint`: applies to all fields and directives in the query
                 */
                $versionConstraint =
                    $directiveArgs[SchemaDefinition::ARGNAME_VERSION_CONSTRAINT]
                    ?? VersioningHelpers::getVersionConstraintsForDirective($this->getDirectiveName())
                    ?? $vars['version-constraint'];
                /**
                 * If the query doesn't restrict the version, then do not process
                 */
                if (!$versionConstraint) {
                    return false;
                }
                /**
                 * Compare using semantic versioning constraint rules, as used by Composer
                 * If passing a wrong value to validate against (eg: "saraza" instead of "1.0.0"), it will throw an Exception
                 */
                try {
                    return Semver::satisfies($schemaDirectiveVersion, $versionConstraint);
                } catch (Exception) {
                    return false;
                }
            }
        }
        return true;
    }

    public function resolveSchemaValidationErrorDescriptions(TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs = []): ?array
    {
        $directiveSchemaDefinition = $this->getSchemaDefinitionForDirective($typeResolver);
        if ($directiveArgsSchemaDefinition = $directiveSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            /**
             * Validate mandatory values
             */
            if (
                $maybeError = $this->maybeValidateNotMissingFieldOrDirectiveArguments(
                    $typeResolver,
                    $directiveName,
                    $directiveArgs,
                    $directiveArgsSchemaDefinition,
                    ResolverTypes::DIRECTIVE
                )
            ) {
                return [$maybeError];
            }

            if ($this->canValidateFieldOrDirectiveArgumentsWithValuesForSchema($directiveArgs)) {
                /**
                 * Validate array types are provided as arrays
                 */
                if (
                    $maybeError = $this->maybeValidateArrayTypeFieldOrDirectiveArguments(
                        $typeResolver,
                        $directiveName,
                        $directiveArgs,
                        $directiveArgsSchemaDefinition,
                        ResolverTypes::DIRECTIVE
                    )
                ) {
                    return [$maybeError];
                }

                /**
                 * Validate enums
                 */
                list(
                    $maybeError
                ) = $this->maybeValidateEnumFieldOrDirectiveArguments(
                    $typeResolver,
                    $directiveName,
                    $directiveArgs,
                    $directiveArgsSchemaDefinition,
                    ResolverTypes::DIRECTIVE
                );
                if ($maybeError) {
                    return [$maybeError];
                }
            }
        }
        return null;
    }

    /**
     * @return mixed[]
     */
    protected function getExpressionsForResultItem(int | string $id, array &$variables, array &$messages): array
    {
        // Create a custom $variables containing all the properties from $dbItems for this resultItem
        // This way, when encountering $propName in a fieldArg in a fieldResolver, it can resolve that value
        // Otherwise it can't, since the fieldResolver doesn't have access to either $dbItems
        return array_merge(
            $variables,
            $messages[self::MESSAGE_EXPRESSIONS][(string)$id] ?? []
        );
    }

    protected function addExpressionForResultItem(int | string $id, string $key, mixed $value, array &$messages): void
    {
        $messages[self::MESSAGE_EXPRESSIONS][(string)$id][$key] = $value;
    }

    protected function getExpressionForResultItem(int | string $id, string $key, array &$messages): mixed
    {
        return $messages[self::MESSAGE_EXPRESSIONS][(string)$id][$key] ?? null;
    }

    /**
     * By default, place the directive after the ResolveAndMerge directive, so the property will be in $dbItems by then
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_RESOLVE;
    }

    /**
     * By default, a directive can be executed only one time for "Schema" and "System"
     * type directives (eg: <translate(en,es),translate(es,en)>),
     * and many times for the other types, "Query" and "Scripting"
     */
    public function isRepeatable(): bool
    {
        return !($this->getDirectiveType() == DirectiveTypes::SYSTEM || $this->getDirectiveType() == DirectiveTypes::SCHEMA);
    }

    /**
     * Indicate if the directive needs to be passed $idsDataFields filled with data to be able to execute
     * Because most commonly it will need, the default value is `true`
     */
    public function needsIDsDataFieldsToExecute(): bool
    {
        return true;
    }

    /**
     * Indicate that there is data in variable $idsDataFields
     */
    protected function hasIDsDataFields(array &$idsDataFields): bool
    {
        foreach ($idsDataFields as $id => &$data_fields) {
            if ($data_fields['direct'] ?? null) {
                // If there's data-fields to fetch for any ID, that's it, there's data
                return true;
            }
        }
        // If we reached here, there is no data
        return false;
    }

    public function getSchemaDirectiveVersion(TypeResolverInterface $typeResolver): ?string
    {
        return null;
    }

    public function enableOrderedSchemaDirectiveArgs(TypeResolverInterface $typeResolver): bool
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->enableOrderedSchemaDirectiveArgs($typeResolver);
        }
        return true;
    }

    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaDirectiveArgs($typeResolver);
        }
        return [];
    }

    public function getFilteredSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            $schemaDirectiveArgs = $schemaDefinitionResolver->getSchemaDirectiveArgs($typeResolver);
        } else {
            $schemaDirectiveArgs = [];
        }
        $this->maybeAddVersionConstraintSchemaFieldOrDirectiveArg(
            $schemaDirectiveArgs,
            !empty($this->getSchemaDirectiveVersion($typeResolver))
        );
        return $schemaDirectiveArgs;
    }

    public function getSchemaDirectiveDeprecationDescription(TypeResolverInterface $typeResolver): ?string
    {
        return $this->getSchemaDefinitionResolver($typeResolver)?->getSchemaDirectiveDeprecationDescription($typeResolver);
    }

    public function resolveSchemaDirectiveDeprecationDescription(TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs = []): ?string
    {
        $directiveSchemaDefinition = $this->getSchemaDefinitionForDirective($typeResolver);
        if ($directiveArgsSchemaDefinition = $directiveSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            list(
                $maybeError,
                $maybeDeprecation
            ) = $this->maybeValidateEnumFieldOrDirectiveArguments(
                $typeResolver,
                $directiveName,
                $directiveArgs,
                $directiveArgsSchemaDefinition,
                ResolverTypes::DIRECTIVE
            );
            if ($maybeDeprecation) {
                return $maybeDeprecation;
            }
        }
        return null;
    }

    public function getSchemaDirectiveWarningDescription(TypeResolverInterface $typeResolver): ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaDirectiveWarningDescription($typeResolver);
        }
        return null;
    }

    public function resolveSchemaDirectiveWarningDescription(TypeResolverInterface $typeResolver): ?string
    {
        if (Environment::enableSemanticVersionConstraints()) {
            /**
             * If restricting the version, and this fieldResolver doesn't have any version, then show a warning
             */
            if ($versionConstraint = $this->directiveArgsForSchema[SchemaDefinition::ARGNAME_VERSION_CONSTRAINT] ?? null) {
                /**
                 * If this fieldResolver doesn't have versioning, then it accepts everything
                 */
                if (!$this->decideCanProcessBasedOnVersionConstraint($typeResolver)) {
                    return sprintf(
                        $this->translationAPI->__('The DirectiveResolver used to process directive \'%s\' (which has version \'%s\') does not pay attention to the version constraint; hence, argument \'versionConstraint\', with value \'%s\', was ignored', 'component-model'),
                        $this->getDirectiveName(),
                        $this->getSchemaDirectiveVersion($typeResolver) ?? '',
                        $versionConstraint
                    );
                }
            }
        }
        return $this->getSchemaDirectiveWarningDescription($typeResolver);
    }

    public function getSchemaDirectiveExpressions(TypeResolverInterface $typeResolver): array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaDirectiveExpressions($typeResolver);
        }
        return [];
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaDirectiveDescription($typeResolver);
        }
        return null;
    }

    public function isGlobal(TypeResolverInterface $typeResolver): bool
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->isGlobal($typeResolver);
        }
        return false;
    }

    public function __invoke($payload)
    {
        // 1. Extract the arguments from the payload
        // $pipelineIDsDataFields is an array containing all stages of the pipe
        // The one corresponding to the current stage is at the head. Take it out from there,
        // and keep passing down the rest of the array to the next stages
        list(
            $typeResolver,
            $pipelineIDsDataFields,
            $pipelineDirectiveResolverInstances,
            $resultIDItems,
            $unionDBKeyIDs,
            $dbItems,
            $previousDBItems,
            $variables,
            $messages,
            $dbErrors,
            $dbWarnings,
            $dbDeprecations,
            $dbNotices,
            $dbTraces,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces
        ) = DirectivePipelineUtils::extractArgumentsFromPayload($payload);

        // Extract the head, keep passing down the rest
        $idsDataFields = $pipelineIDsDataFields[0];
        array_shift($pipelineIDsDataFields);
        // The $pipelineDirectiveResolverInstances is the series of directives executed in the pipeline
        // The current stage is at the head. Remove it
        array_shift($pipelineDirectiveResolverInstances);

        // // 2. Validate operation
        // $this->validateDirective(
        //     $typeResolver,
        //     $idsDataFields,
        //     $pipelineIDsDataFields,
        //     $pipelineDirectiveResolverInstances,
        //     $resultIDItems,
        //     $dbItems,
        //     $previousDBItems,
        //     $variables,
        //     $messages,
        //     $dbErrors,
        //     $dbWarnings,
        //     $dbDeprecations,
        //     $dbNotices,
        //     $dbTraces,
        //     $schemaErrors,
        //     $schemaWarnings,
        //     $schemaDeprecations,
        //     $schemaNotices,
        //     $schemaTraces
        // );

        // 2. Execute operation.
        // First check that if the validation took away the elements, and so the directive can't execute anymore
        // For instance, executing ?query=posts.id|title<default,translate(from:en,to:es)> will fail
        // after directive "default", so directive "translate" must not even execute
        if (!$this->needsIDsDataFieldsToExecute() || $this->hasIDsDataFields($idsDataFields)) {
            $this->resolveDirective(
                $typeResolver,
                $idsDataFields,
                $pipelineIDsDataFields,
                $pipelineDirectiveResolverInstances,
                $resultIDItems,
                $unionDBKeyIDs,
                $dbItems,
                $previousDBItems,
                $variables,
                $messages,
                $dbErrors,
                $dbWarnings,
                $dbDeprecations,
                $dbNotices,
                $dbTraces,
                $schemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
                $schemaNotices,
                $schemaTraces
            );
        }

        // 3. Re-create the payload from the modified variables
        return DirectivePipelineUtils::convertArgumentsToPayload(
            $typeResolver,
            $pipelineIDsDataFields,
            $pipelineDirectiveResolverInstances,
            $resultIDItems,
            $unionDBKeyIDs,
            $dbItems,
            $previousDBItems,
            $variables,
            $messages,
            $dbErrors,
            $dbWarnings,
            $dbDeprecations,
            $dbNotices,
            $dbTraces,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces
        );
    }

    // public function validateDirective(TypeResolverInterface $typeResolver, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$resultIDItems, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations)
    // {
    //     // Check that the directive can be applied to all provided fields
    //     $this->validateAndFilterFieldsForDirective($idsDataFields, $schemaErrors, $schemaWarnings);
    // }
    // /**
    //  * Check that the directive can be applied to all provided fields
    //  *
    //  * @param array $idsDataFields
    //  * @param array $schemaErrors
    //  */
    // protected function validateAndFilterFieldsForDirective(array &$idsDataFields, array &$schemaErrors, array &$schemaWarnings)
    // {
    //     $directiveSupportedFieldNames = $this->getFieldNamesToApplyTo();
    //     // If this function returns an empty array, then it supports all fields, then do nothing
    //     if (!$directiveSupportedFieldNames) {
    //         return;
    //     }
    //     // Check if all fields are supported by this directive
    //     $failedFields = [];
    //     foreach ($idsDataFields as $id => &$data_fields) {
    //         // Get the fieldName for each field
    //         $nameFields = [];
    //         foreach ($data_fields['direct'] as $field) {
    //             $nameFields[$this->fieldQueryInterpreter->getFieldName($field)] = $field;
    //         }
    //         // If any fieldName failed, remove it from the list of fields to execute for this directive
    //         if ($unsupportedFieldNames = array_diff(array_keys($nameFields), $directiveSupportedFieldNames)) {
    //             $unsupportedFields = array_map(
    //                 function($fieldName) use ($nameFields) {
    //                     return $nameFields[$fieldName];
    //                 },
    //                 $unsupportedFieldNames
    //             );
    //             $failedFields = array_values(array_unique(array_merge(
    //                 $failedFields,
    //                 $unsupportedFields
    //             )));
    //         }
    //     }
    //     // Give an error message for all failed fields
    //     if ($failedFields) {
    //         $directiveName = $this->getDirectiveName();
    //         $failedFieldNames = array_map(
    //             [$this->fieldQueryInterpreter, 'getFieldName'],
    //             $failedFields
    //         );
    //         if (count($failedFields) == 1) {
    //             $message = $this->translationAPI->__('Directive \'%s\' doesn\'t support field \'%s\' (the only supported field names are: \'%s\')', 'component-model');
    //         } else {
    //             $message = $this->translationAPI->__('Directive \'%s\' doesn\'t support fields \'%s\' (the only supported field names are: \'%s\')', 'component-model');
    //         }
    //         $failureMessage = sprintf(
    //             $message,
    //             $directiveName,
    //             implode($this->translationAPI->__('\', \''), $failedFieldNames),
    //             implode($this->translationAPI->__('\', \''), $directiveSupportedFieldNames)
    //         );
    //         $this->processFailure($failureMessage, $failedFields, $idsDataFields, $schemaErrors, $schemaWarnings);
    //     }
    // }
    /**
     * Depending on environment configuration, either show a warning,
     * or show an error and remove the fields from the directive pipeline for further execution
     */
    protected function processFailure(string $failureMessage, array $failedFields, array &$idsDataFields, array &$succeedingPipelineIDsDataFields, array &$schemaErrors, array &$schemaWarnings): void
    {
        $allFieldsFailed = empty($failedFields);
        if ($allFieldsFailed) {
            // Remove all fields
            $idsDataFieldsToRemove = $idsDataFields;
            // Calculate which fields are being removed, to add to the error
            foreach ($idsDataFields as $id => &$data_fields) {
                $failedFields = array_merge(
                    $failedFields,
                    $data_fields['direct']
                );
            }
            $failedFields = array_values(array_unique($failedFields));
        } else {
            $idsDataFieldsToRemove = [];
            // Calculate which fields to remove
            foreach ($idsDataFields as $id => &$data_fields) {
                $idsDataFieldsToRemove[(string)$id]['direct'] = array_intersect(
                    $data_fields['direct'],
                    $failedFields
                );
            }
        }
        // If the failure must be processed as an error, we must also remove the fields from the directive pipeline
        $removeFieldIfDirectiveFailed = Environment::removeFieldIfDirectiveFailed();
        if ($removeFieldIfDirectiveFailed) {
            $this->removeIDsDataFields($idsDataFieldsToRemove, $succeedingPipelineIDsDataFields);
        }

        // Show the failureMessage either as error or as warning
        $directiveName = $this->getDirectiveName();
        // $failedFieldNames = array_map(
        //     [$this->fieldQueryInterpreter, 'getFieldName'],
        //     $failedFields
        // );
        if ($removeFieldIfDirectiveFailed) {
            if (count($failedFields) == 1) {
                $message = $this->translationAPI->__('%s. Field \'%s\' has been removed from the directive pipeline', 'component-model');
            } else {
                $message = $this->translationAPI->__('%s. Fields \'%s\' have been removed from the directive pipeline', 'component-model');
            }
            $schemaErrors[] = [
                Tokens::PATH => [implode($this->translationAPI->__('\', \''), $failedFields), $this->directive],
                Tokens::MESSAGE => sprintf(
                    $message,
                    $failureMessage,
                    implode($this->translationAPI->__('\', \''), $failedFields)
                ),
            ];
        } else {
            if (count($failedFields) == 1) {
                $message = $this->translationAPI->__('%s. Execution of directive \'%s\' has been ignored on field \'%s\'', 'component-model');
            } else {
                $message = $this->translationAPI->__('%s. Execution of directive \'%s\' has been ignored on fields \'%s\'', 'component-model');
            }
            $schemaWarnings[] = [
                Tokens::PATH => [implode($this->translationAPI->__('\', \''), $failedFields), $this->directive],
                Tokens::MESSAGE => sprintf(
                    $message,
                    $failureMessage,
                    $directiveName,
                    implode($this->translationAPI->__('\', \''), $failedFields)
                ),
            ];
        }
    }

    public function getSchemaDefinitionResolver(TypeResolverInterface $typeResolver): ?SchemaDirectiveResolverInterface
    {
        return null;
    }

    /**
     * Directives may not be directly visible in the schema,
     * eg: because their name is duplicated across directives (eg: "cacheControl")
     * or because they are used through code (eg: "validateIsUserLoggedIn")
     */
    public function skipAddingToSchemaDefinition(): bool
    {
        return false;
    }

    public function getSchemaDefinitionForDirective(TypeResolverInterface $typeResolver): array
    {
        $directiveName = $this->getDirectiveName();
        $schemaDefinition = [
            SchemaDefinition::ARGNAME_NAME => $directiveName,
            SchemaDefinition::ARGNAME_DIRECTIVE_TYPE => $this->getDirectiveType(),
            SchemaDefinition::ARGNAME_DIRECTIVE_PIPELINE_POSITION => $this->getPipelinePosition(),
            SchemaDefinition::ARGNAME_DIRECTIVE_IS_REPEATABLE => $this->isRepeatable(),
            SchemaDefinition::ARGNAME_DIRECTIVE_NEEDS_DATA_TO_EXECUTE => $this->needsIDsDataFieldsToExecute(),
        ];
        if ($limitedToFields = $this->getFieldNamesToApplyTo()) {
            $schemaDefinition[SchemaDefinition::ARGNAME_DIRECTIVE_LIMITED_TO_FIELDS] = $limitedToFields;
        }
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            if ($description = $schemaDefinitionResolver->getSchemaDirectiveDescription($typeResolver)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
            }
            if ($expressions = $schemaDefinitionResolver->getSchemaDirectiveExpressions($typeResolver)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DIRECTIVE_EXPRESSIONS] = $expressions;
            }
            if ($deprecationDescription = $schemaDefinitionResolver->getSchemaDirectiveDeprecationDescription($typeResolver)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] = true;
                $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
            }
            if ($args = $schemaDefinitionResolver->getFilteredSchemaDirectiveArgs($typeResolver)) {
                // Add the args under their name.
                // Watch out: the name is mandatory!
                // If it hasn't been set, then skip the entry
                $nameArgs = [];
                foreach ($args as $arg) {
                    if (!isset($arg[SchemaDefinition::ARGNAME_NAME])) {
                        continue;
                    }
                    $nameArgs[$arg[SchemaDefinition::ARGNAME_NAME]] = $arg;
                }
                $schemaDefinition[SchemaDefinition::ARGNAME_ARGS] = $nameArgs;
            }
        }
        /**
         * Please notice: the version always comes from the directiveResolver, and not from the schemaDefinitionResolver
         * That is because it is the implementer the one who knows what version it is, and not the one defining the interface
         * If the interface changes, the implementer will need to change, so the version will be upgraded
         * But it could also be that the contract doesn't change, but the implementation changes
         * it's really not their responsibility
         */
        if (Environment::enableSemanticVersionConstraints()) {
            if ($version = $this->getSchemaDirectiveVersion($typeResolver)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_VERSION] = $version;
            }
        }
        $this->addSchemaDefinitionForDirective($schemaDefinition);
        return $schemaDefinition;
    }

    /**
     * Function to override
     */
    protected function addSchemaDefinitionForDirective(array &$schemaDefinition)
    {
    }
}
