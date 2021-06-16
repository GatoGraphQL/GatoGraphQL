<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use League\Pipeline\PipelineBuilder;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;
use PoP\ComponentModel\Facades\Engine\DataloadingEngineFacade;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolverDecorators\TypeResolverDecoratorInterface;
use PoP\ComponentModel\TypeResolvers\FieldHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionTypeHelpers;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryUtils;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractTypeResolver implements TypeResolverInterface
{
    public const OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM = 'validateSchemaOnResultItem';

    /**
     * Cache of which fieldResolvers will process the given field
     *
     * @var array<string, FieldResolverInterface[]>
     */
    protected array $fieldResolvers = [];
    /**
     * @var array<string, array>
     */
    protected ?array $schemaDefinition = null;
    /**
     * @var array<string,DirectiveResolverInterface[]>|null
     */
    protected ?array $directiveNameResolvers = null;
    /**
     * @var array<string, FieldResolverInterface>|null
     */
    protected ?array $schemaFieldResolvers = null;
    /**
     * @var string[]|null
     */
    protected ?array $typeResolverDecorators = null;
    /**
     * @var array<string, array>|null
     */
    protected ?array $mandatoryDirectivesForFields = null;
    /**
     * @var array<string, array>|null
     */
    protected ?array $precedingMandatoryDirectivesForDirectives = null;
    /**
     * @var array<string, array>|null
     */
    protected ?array $succeedingMandatoryDirectivesForDirectives = null;
    /**
     * @var string[]|null
     */
    protected ?array $interfaceClasses = null;
    /**
     * @var array<FieldInterfaceResolverInterface>|null
     */
    protected ?array $interfaceResolverInstances = null;

    /**
     * @var array<string, array>
     */
    private array $fieldDirectiveIDFields = [];
    /**
     * @var array<string, string>
     */
    private array $fieldDirectivesFromFieldCache = [];
    /**
     * @var array<string, array>
     */
    private array $dissectedFieldForSchemaCache = [];
    /**
     * @var array<string, array>
     */
    private array $directiveResolverInstanceCache = [];
    /**
     * @var array<string, array>
     */
    private array $fieldNamesResolvedByFieldResolver = [];

    public function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected HooksAPIInterface $hooksAPI,
        protected InstanceManagerInterface $instanceManager,
        protected FeedbackMessageStoreInterface $feedbackMessageStore,
        protected FieldQueryInterpreterInterface $fieldQueryInterpreter,
        protected ErrorProviderInterface $errorProvider,
        protected SchemaDefinitionServiceInterface $schemaDefinitionService,
    ) {
    }

    public function getNamespace(): string
    {
        return SchemaHelpers::getSchemaNamespace(get_called_class());
    }

    final public function getNamespacedTypeName(): string
    {
        return SchemaHelpers::getSchemaNamespacedName(
            $this->getNamespace(),
            $this->getTypeName()
        );
    }

    final public function getMaybeNamespacedTypeName(): string
    {
        $vars = ApplicationState::getVars();
        return $vars['namespace-types-and-interfaces'] ?
            $this->getNamespacedTypeName() :
            $this->getTypeName();
    }

    public function getTypeOutputName(): string
    {
        // Do not make the first letter lowercase, or namespaced names look bad
        return $this->getMaybeNamespacedTypeName();
    }

    public function getSchemaTypeDescription(): ?string
    {
        return null;
    }

    /**
     * @return array<string,DirectiveResolverInterface[]>
     */
    public function getDirectiveNameResolvers(): array
    {
        if (is_null($this->directiveNameResolvers)) {
            $this->directiveNameResolvers = $this->calculateFieldDirectiveNameResolvers();
        }
        return $this->directiveNameResolvers;
    }

    public function getIdFieldTypeResolverClass(): string
    {
        return get_called_class();
    }

    /**
     * @param $dbObjectIDOrIDs string|int|array<string|int>
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string | int | array $dbObjectIDOrIDs): string | int | array
    {
        // Add the type before the ID
        $dbObjectIDs = is_array($dbObjectIDOrIDs) ? $dbObjectIDOrIDs : [$dbObjectIDOrIDs];
        $qualifiedDBObjectIDs = array_map(
            /**
             * Commented temporarily until Rector can downgrade union types on anonymous functions
             * @see https://github.com/rectorphp/rector/issues/5989
             */
            // function (int | string $id) {
            function ($id) {
                return UnionTypeHelpers::getDBObjectComposedTypeAndID(
                    $this,
                    $id
                );
            },
            $dbObjectIDs
        );
        return is_array($dbObjectIDOrIDs) ? $qualifiedDBObjectIDs : $qualifiedDBObjectIDs[0];
    }

    public function qualifyDBObjectIDsToRemoveFromErrors(): bool
    {
        return false;
    }

    /**
    * By default, the pipeline must always have directives:
    * 1. Validate: to validate that the schema, fieldNames, etc are supported, and filter them out if not
    * 2. ResolveAndMerge: to resolve the field and place the data into the DB object
    * Additionally to these 2, we can add other mandatory directives, such as:
    * setSelfAsExpression, cacheControl
    * Because it may be more convenient to add the directive or the class, there are 2 methods
    */
    protected function getMandatoryDirectives()
    {
        $dataloadingEngine = DataloadingEngineFacade::getInstance();
        return array_map(
            function ($directiveResolver) {
                return $this->fieldQueryInterpreter->listFieldDirective($directiveResolver->getDirectiveName());
            },
            $dataloadingEngine->getMandatoryDirectiveResolvers()
        );
    }

    /**
     * Validate and resolve the fieldDirectives into an array, each item containing:
     * 1. the directiveResolverInstance
     * 2. its fieldDirective
     * 3. the fields it affects
     */
    public function resolveDirectivesIntoPipelineData(
        array $fieldDirectives,
        array &$fieldDirectiveFields,
        bool $areNestedDirectives,
        array &$variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array {
        /**
        * All directives are placed somewhere in the pipeline. There are 5 positions:
        * 1. At the beginning
        * 2. Before Validate directive
        * 3. Between the Validate and Resolve directives
        * 4. After the ResolveAndMerge directive
        * 4. At the end
        */
        $directiveInstancesByPosition = $fieldDirectivesByPosition = $directiveFieldsByPosition = [
            PipelinePositions::BEGINNING => [],
            PipelinePositions::BEFORE_VALIDATE => [],
            PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE => [],
            PipelinePositions::AFTER_RESOLVE => [],
            PipelinePositions::END => [],
        ];

        // Resolve from directive into their actual object instance.
        $directiveSchemaErrors = $directiveSchemaWarnings = $directiveSchemaDeprecations = $directiveSchemaNotices = $directiveSchemaTraces = [];
        $directiveResolverInstanceData = $this->validateAndResolveInstances(
            $fieldDirectives,
            $fieldDirectiveFields,
            $variables,
            $directiveSchemaErrors,
            $directiveSchemaWarnings,
            $directiveSchemaDeprecations,
            $directiveSchemaNotices,
            $directiveSchemaTraces
        );
        // If it is a root directives, then add the fields where they appear into the errors/warnings/deprecations
        if (!$areNestedDirectives) {
            // In the case of an error, Maybe prepend the field(s) containing the directive.
            // Eg: when the directive name doesn't exist: /?query=id<skipanga>
            foreach ($directiveSchemaErrors as $directiveSchemaError) {
                $directive = $directiveSchemaError[Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    foreach ($directiveFields as $directiveField) {
                        $schemaError = $directiveSchemaError;
                        array_unshift($schemaError[Tokens::PATH], $directiveField);
                        $this->prependPathOnNestedErrors($schemaError, $directiveField);
                        $schemaErrors[] = $schemaError;
                    }
                } else {
                    $schemaErrors[] = $directiveSchemaError;
                }
            }
            foreach ($directiveSchemaWarnings as $directiveSchemaWarning) {
                $directive = $directiveSchemaWarning[Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    foreach ($directiveFields as $directiveField) {
                        $schemaWarning = $directiveSchemaWarning;
                        array_unshift($schemaWarning[Tokens::PATH], $directiveField);
                        $schemaWarnings[] = $schemaWarning;
                    }
                } else {
                    $schemaWarnings[] = $directiveSchemaWarning;
                }
            }
            foreach ($directiveSchemaDeprecations as $directiveSchemaDeprecation) {
                $directive = $directiveSchemaDeprecation[Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    foreach ($directiveFields as $directiveField) {
                        $schemaDeprecation = $directiveSchemaDeprecation;
                        array_unshift($schemaDeprecation[Tokens::PATH], $directiveField);
                        $schemaDeprecations[] = $schemaDeprecation;
                    }
                } else {
                    $schemaDeprecations[] = $directiveSchemaDeprecation;
                }
            }
            foreach ($directiveSchemaNotices as $directiveSchemaNotice) {
                $directive = $directiveSchemaNotice[Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    foreach ($directiveFields as $directiveField) {
                        $schemaNotice = $directiveSchemaNotice;
                        array_unshift($schemaNotice[Tokens::PATH], $directiveField);
                        $schemaNotices[] = $schemaNotice;
                    }
                } else {
                    $schemaNotices[] = $directiveSchemaNotice;
                }
            }
            foreach ($directiveSchemaTraces as $directiveSchemaTrace) {
                $directive = $directiveSchemaTrace[Tokens::PATH][0];
                if ($directiveFields = $fieldDirectiveFields[$directive] ?? null) {
                    foreach ($directiveFields as $directiveField) {
                        $schemaTrace = $directiveSchemaTrace;
                        array_unshift($schemaTrace[Tokens::PATH], $directiveField);
                        $schemaTraces[] = $schemaTrace;
                    }
                } else {
                    $schemaTraces[] = $directiveSchemaTrace;
                }
            }
        } else {
            $schemaErrors = array_merge(
                $schemaErrors,
                $directiveSchemaErrors
            );
            $schemaWarnings = array_merge(
                $schemaWarnings,
                $directiveSchemaWarnings
            );
            $schemaDeprecations = array_merge(
                $schemaDeprecations,
                $directiveSchemaDeprecations
            );
            $schemaNotices = array_merge(
                $schemaNotices,
                $directiveSchemaNotices
            );
            $schemaTraces = array_merge(
                $schemaTraces,
                $directiveSchemaTraces
            );
        }

        // Create an array with the dataFields affected by each directive, in order in which they will be invoked
        foreach ($directiveResolverInstanceData as $instanceID => $directiveResolverInstanceData) {
            // Add the directive in its required position in the pipeline, and retrieve what fields it will process
            $directiveResolverInstance = $directiveResolverInstanceData['instance'];
            $pipelinePosition = $directiveResolverInstance->getPipelinePosition();
            $directiveInstancesByPosition[$pipelinePosition][] = $directiveResolverInstance;
            $fieldDirectivesByPosition[$pipelinePosition][] = $directiveResolverInstanceData['fieldDirective'];
            $directiveFieldsByPosition[$pipelinePosition][] = $directiveResolverInstanceData['fields'];
        }
        // Once we have them ordered, we can simply discard the positions, keep only the values
        // Each item has 3 elements: the directiveResolverInstance, its fieldDirective, and the fields it affects
        $pipelineData = [];
        foreach ($directiveInstancesByPosition as $position => $directiveResolverInstances) {
            for ($i = 0; $i < count($directiveResolverInstances); $i++) {
                $pipelineData[] = [
                    'instance' => $directiveResolverInstances[$i],
                    'fieldDirective' => $fieldDirectivesByPosition[$position][$i],
                    'fields' => $directiveFieldsByPosition[$position][$i],
                ];
            }
        }
        return $pipelineData;
    }

    /**
     * Add the field(s) to the head of the error path, for all nested errors
     */
    protected function prependPathOnNestedErrors(array &$directiveSchemaError, string $directiveField): void {
        
        if (isset($directiveSchemaError[Tokens::EXTENSIONS][Tokens::NESTED])) {
            foreach ($directiveSchemaError[Tokens::EXTENSIONS][Tokens::NESTED] as &$nestedDirectiveSchemaError) {
                array_unshift($nestedDirectiveSchemaError[Tokens::PATH], $directiveField);
                $this->prependPathOnNestedErrors($nestedDirectiveSchemaError, $directiveField);
            }
        }
    }

    public function getDirectivePipeline(array $directiveResolverInstances): DirectivePipelineDecorator
    {
        // From the ordered directives, create the pipeline
        $pipelineBuilder = new PipelineBuilder();
        foreach ($directiveResolverInstances as $directiveResolverInstance) {
            $pipelineBuilder->add($directiveResolverInstance);
        }
        $directivePipeline = new DirectivePipelineDecorator($pipelineBuilder->build());
        return $directivePipeline;
    }

    protected function validateAndResolveInstances(
        array $fieldDirectives,
        array $fieldDirectiveFields,
        array &$variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array {
        $instances = [];
        // Count how many times each directive is added
        $directiveFieldTrack = [];
        $directiveResolverInstanceFields = [];
        $fieldDirectivesCount = count($fieldDirectives);
        for ($i = 0; $i < $fieldDirectivesCount; $i++) {
            // Because directives can be repeated inside a field (eg: <resize(50%),resize(50%)>),
            // then we deal with 2 variables:
            // 1. $fieldDirective: the actual directive
            // 2. $enqueuedFieldDirective: how it was added to the array
            // For retrieving the idsDataFields for the directive, we'll use $enqueuedFieldDirective, since under this entry we stored all the data in the previous functions
            // For everything else, we use $fieldDirective
            $enqueuedFieldDirective = $fieldDirectives[$i];
            // Check if it is a repeated directive: if it has the "|" symbol
            $counterSeparatorPos = QueryUtils::findLastSymbolPosition(
                $enqueuedFieldDirective,
                FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR,
                [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING],
                [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING],
                QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING,
                QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING
            );
            $isRepeatedFieldDirective = $counterSeparatorPos !== false;
            if ($isRepeatedFieldDirective) {
                // Remove the "|counter" bit from the fieldDirective
                $fieldDirective = substr($enqueuedFieldDirective, 0, $counterSeparatorPos);
            } else {
                $fieldDirective = $enqueuedFieldDirective;
            }

            $fieldDirectiveResolverInstances = $this->getDirectiveResolverInstanceForDirective($fieldDirective, $fieldDirectiveFields[$enqueuedFieldDirective], $variables);
            $directiveName = $this->fieldQueryInterpreter->getFieldDirectiveName($fieldDirective);
            // If there is no directive with this name, show an error and skip it
            if (is_null($fieldDirectiveResolverInstances)) {
                $schemaErrors[] = [
                    Tokens::PATH => [$fieldDirective],
                    Tokens::MESSAGE => sprintf(
                        $this->translationAPI->__('No DirectiveResolver resolves directive with name \'%s\'', 'pop-component-model'),
                        $directiveName
                    ),
                ];
                continue;
            }
            $directiveArgs = $this->fieldQueryInterpreter->extractStaticDirectiveArguments($fieldDirective);

            if (empty($fieldDirectiveResolverInstances)) {
                $schemaErrors[] = [
                    Tokens::PATH => [$fieldDirective],
                    Tokens::MESSAGE => sprintf(
                        $this->translationAPI->__('No DirectiveResolver processes directive with name \'%s\' and arguments \'%s\' in field(s) \'%s\'', 'pop-component-model'),
                        $directiveName,
                        json_encode($directiveArgs),
                        implode(
                            $this->translationAPI->__('\', \'', 'pop-component-model'),
                            $fieldDirectiveFields[$fieldDirective]
                        )
                    ),
                ];
                continue;
            }

            foreach ($fieldDirectiveFields[$enqueuedFieldDirective] as $field) {
                $directiveResolverInstance = $fieldDirectiveResolverInstances[$field];
                if (is_null($directiveResolverInstance)) {
                    $schemaErrors[] = [
                        Tokens::PATH => [$fieldDirective],
                        Tokens::MESSAGE => sprintf(
                            $this->translationAPI->__('No DirectiveResolver processes directive with name \'%s\' and arguments \'%s\' in field \'%s\'', 'pop-component-model'),
                            $directiveName,
                            json_encode($directiveArgs),
                            $field
                        ),
                    ];
                    continue;
                }

                // Consolidate the same directiveResolverInstances for different fields,
                // as to do the validation only once on each of them
                $instanceID = get_class($directiveResolverInstance) . $enqueuedFieldDirective;
                if (!isset($directiveResolverInstanceFields[$instanceID])) {
                    $directiveResolverInstanceFields[$instanceID]['fieldDirective'] = $fieldDirective;
                    $directiveResolverInstanceFields[$instanceID]['enqueuedFieldDirective'] = $enqueuedFieldDirective;
                    $directiveResolverInstanceFields[$instanceID]['instance'] = $directiveResolverInstance;
                }
                $directiveResolverInstanceFields[$instanceID]['fields'][] = $field;
            }
        }

        // Validate all the directiveResolvers in the field
        foreach ($directiveResolverInstanceFields as $instanceID => $instanceData) {
            $fieldDirective = $instanceData['fieldDirective'];
            $enqueuedFieldDirective = $instanceData['enqueuedFieldDirective'];
            $directiveResolverInstance = $instanceData['instance'];
            $directiveResolverFields = $instanceData['fields'];
            // If the enqueued and the fieldDirective are different, it's because it is a repeated one
            $isRepeatedFieldDirective = $fieldDirective != $enqueuedFieldDirective;
            // If it is a repeated directive, no need to do the validation again
            if ($isRepeatedFieldDirective) {
                // If there is an existing error, then skip adding this resolver to the pipeline
                if (
                    !empty(array_filter(
                        $schemaErrors,
                        function ($schemaError) use ($fieldDirective) {
                            return $schemaError[Tokens::PATH][0] == $fieldDirective;
                        }
                    ))
                ) {
                    continue;
                }
            } else {
                // Validate schema (eg of error in schema: ?query=posts<include(if:this-field-doesnt-exist())>)
                $fieldSchemaErrors = [];
                list(
                    $validFieldDirective,
                    $directiveName,
                    $directiveArgs,
                ) = $directiveResolverInstance->dissectAndValidateDirectiveForSchema(
                    $this,
                    $fieldDirectiveFields,
                    $variables,
                    $fieldSchemaErrors,
                    $schemaWarnings,
                    $schemaDeprecations,
                    $schemaNotices,
                    $schemaTraces
                );
                if ($fieldSchemaErrors) {
                    $schemaErrors = array_merge(
                        $schemaErrors,
                        $fieldSchemaErrors
                    );
                    continue;
                }

                // Validate against the directiveResolver
                if ($maybeErrors = $directiveResolverInstance->resolveSchemaValidationErrorDescriptions($this, $directiveName, $directiveArgs)) {
                    foreach ($maybeErrors as $error) {
                        $schemaErrors[] = [
                            Tokens::PATH => [$fieldDirective],
                            Tokens::MESSAGE => $error,
                        ];
                    }
                    continue;
                }

                // Check for warnings
                if ($warningDescription = $directiveResolverInstance->resolveSchemaDirectiveWarningDescription($this)) {
                    $schemaWarnings[] = [
                        Tokens::PATH => [$fieldDirective],
                        Tokens::MESSAGE => $warningDescription,
                    ];
                }

                // Check for deprecations
                if ($deprecationDescription = $directiveResolverInstance->getSchemaDirectiveDeprecationDescription($this)) {
                    $schemaDeprecations[] = [
                        Tokens::PATH => [$fieldDirective],
                        Tokens::MESSAGE => $deprecationDescription,
                    ];
                }
            }

            // Validate if the directive can be executed multiple times on each field
            if (!$directiveResolverInstance->isRepeatable()) {
                // Check if the directive is already processing any of the fields
                $directiveName = $this->fieldQueryInterpreter->getFieldDirectiveName($fieldDirective);
                $alreadyProcessingFields = array_intersect(
                    $directiveFieldTrack[$directiveName] ?? [],
                    $directiveResolverFields
                );
                $directiveFieldTrack[$directiveName] = array_unique(array_merge(
                    $directiveFieldTrack[$directiveName] ?? [],
                    $directiveResolverFields
                ));
                if ($alreadyProcessingFields) {
                    // Remove the fields from this iteration, and add an error
                    $directiveResolverFields = array_diff(
                        $directiveResolverFields,
                        $alreadyProcessingFields
                    );
                    $schemaErrors[] = [
                        Tokens::PATH => [$fieldDirective],
                        Tokens::MESSAGE => sprintf(
                            $this->translationAPI->__('Directive \'%s\' can be executed only once for field(s) \'%s\'', 'component-model'),
                            $fieldDirective,
                            implode('\', \'', $alreadyProcessingFields)
                        ),
                    ];
                    // If after removing the duplicated fields there are still others, process them
                    // Otherwise, skip
                    if (!$directiveResolverFields) {
                        continue;
                    }
                }
            }

            // Directive is valid. Add it under its instanceID, which enables to add fields under the same directiveResolverInstance
            $instances[$instanceID]['instance'] = $directiveResolverInstance;
            $instances[$instanceID]['fieldDirective'] = $fieldDirective;
            $instances[$instanceID]['fields'] = $directiveResolverFields;
        }
        return $instances;
    }

    public function getDirectiveResolverInstanceForDirective(string $fieldDirective, array $fieldDirectiveFields, array &$variables): ?array
    {
        $directiveName = $this->fieldQueryInterpreter->getFieldDirectiveName($fieldDirective);
        $directiveArgs = $this->fieldQueryInterpreter->extractStaticDirectiveArguments($fieldDirective);

        $directiveNameResolvers = $this->getDirectiveNameResolvers();
        $directiveResolvers = $directiveNameResolvers[$directiveName] ?? null;
        if ($directiveResolvers === null) {
            return null;
        }

        // Calculate directives per field
        $fieldDirectiveResolverInstances = [];
        foreach ($fieldDirectiveFields as $field) {
            // Check that at least one class which deals with this directiveName can satisfy the directive (for instance, validating that a required directiveArg is present)
            $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
            foreach ($directiveResolvers as $directiveResolver) {
                $directiveSupportedFieldNames = $directiveResolver->getFieldNamesToApplyTo();
                // If this field is not supported by the directive, skip
                if ($directiveSupportedFieldNames && !in_array($fieldName, $directiveSupportedFieldNames)) {
                    continue;
                }
                $directiveClass = get_class($directiveResolver);
                // Get the instance from the cache if it exists, or create it if not
                if (!isset($this->directiveResolverInstanceCache[$directiveClass][$fieldDirective])) {
                    $this->directiveResolverInstanceCache[$directiveClass][$fieldDirective] = new $directiveClass($fieldDirective);
                }
                $maybeDirectiveResolverInstance = $this->directiveResolverInstanceCache[$directiveClass][$fieldDirective];
                // Check if this instance can process the directive
                if ($maybeDirectiveResolverInstance->resolveCanProcess($this, $directiveName, $directiveArgs, $field, $variables)) {
                    $fieldDirectiveResolverInstances[$field] = $maybeDirectiveResolverInstance;
                    break;
                }
            }
        }
        return $fieldDirectiveResolverInstances;
    }

    /**
     * By default, do nothing
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function validateFieldArgumentsForSchema(string $field, array $fieldArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        return $fieldArgs;
    }

    /**
     * @return mixed[]
     */
    protected function getIDsToQuery(array $ids_data_fields): array
    {
        return array_keys($ids_data_fields);
    }

    protected function getUnresolvedResultItemIDError(string | int $resultItemID)
    {
        return new Error(
            'unresolved-resultitem-id',
            sprintf(
                $this->translationAPI->__('The DataLoader can\'t load data for object of type \'%s\' with ID \'%s\'', 'pop-component-model'),
                $this->getTypeOutputName(),
                $resultItemID
            )
        );
    }

    public function fillResultItems(
        array $ids_data_fields,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array {
        // Obtain the data for the required object IDs
        $resultIDItems = [];
        $ids = $this->getIDsToQuery($ids_data_fields);
        $typeDataLoaderClass = $this->getTypeDataLoaderClass();
        $typeDataLoader = $this->instanceManager->getInstance($typeDataLoaderClass);
        // If any ID cannot be resolved, the resultItem will be null
        $resultItems = array_filter($typeDataLoader->getObjects($ids));
        foreach ($resultItems as $resultItem) {
            $resultItemID = $this->getID($resultItem);
            // If the UnionTypeResolver doesn't have a TypeResolver to process this element, the ID will be null, and an error will be show below
            if ($resultItemID === null) {
                continue;
            }
            $resultIDItems[$resultItemID] = $resultItem;
        }
        // Show an error for all resultItems that couldn't be processed
        $resolvedResultItemIDs = $this->getIDsToQuery($resultIDItems);
        $unresolvedResultItemIDs = [];
        foreach (array_diff($ids, $resolvedResultItemIDs) as $unresolvedResultItemID) {
            $error = $this->getUnresolvedResultItemIDError($unresolvedResultItemID);
            // If a UnionTypeResolver fails to load an object, the fields will be NULL
            $failedFields = $ids_data_fields[$unresolvedResultItemID]['direct'] ?? [];
            // Add in $schemaErrors instead of $dbErrors because in the latter one it will attempt to fetch the ID from the object, which it can't do
            foreach ($failedFields as $failedField) {
                $schemaErrors[] = [
                    Tokens::PATH => [$failedField],
                    Tokens::MESSAGE => $error->getMessageOrCode(),
                ];
            }

            // Indicate that this ID must be removed from the results
            $unresolvedResultItemIDs[] = $unresolvedResultItemID;
        }
        // Remove all the IDs that failed from the elements to process, so it doesn't show a "Corrupted Data" error
        // Because these are IDs (eg: 223) and $ids_data_fields contains qualified or typed IDs (eg: post/223), we must convert them first
        if ($unresolvedResultItemIDs) {
            if ($this->qualifyDBObjectIDsToRemoveFromErrors()) {
                $unresolvedResultItemIDs = $this->getQualifiedDBObjectIDOrIDs($unresolvedResultItemIDs);
            }
            $ids_data_fields = array_filter(
                $ids_data_fields,
                /**
                 * Commented temporarily until Rector can downgrade union types on anonymous functions
                 * @see https://github.com/rectorphp/rector/issues/5989
                 */
                // function (int | string $id) use ($unresolvedResultItemIDs) {
                function ($id) use ($unresolvedResultItemIDs) {
                    return !in_array($id, $unresolvedResultItemIDs);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        // Enqueue the items
        $this->enqueueFillingResultItemsFromIDs($ids_data_fields);

        // Process them
        $this->processFillingResultItemsFromIDs(
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

        return $resultIDItems;
    }

    /**
     * Given an array of directives, attach, before and after each of them, their own mandatory directives
     * Eg: a directive `@validateDoesUserHaveCapability` must be preceded by a directive `@validateIsUserLoggedIn`
     *
     * The process is recursive: mandatory directives can have their own mandatory directives, and these are added too
     */
    protected function addMandatoryDirectivesForDirectives(array $directives): array
    {
        $precedingMandatoryDirectivesForDirectives = $this->getAllPrecedingMandatoryDirectivesForDirectives();
        $succeedingMandatoryDirectivesForDirectives = $this->getAllSucceedingMandatoryDirectivesForDirectives();
        $allDirectives = [];
        foreach ($directives as $directive) {
            $directiveName = $this->fieldQueryInterpreter->getDirectiveName($directive);
            // Add preceding mandatory directives
            if (
                $mandatoryDirectivesForDirective = array_merge(
                    $precedingMandatoryDirectivesForDirectives[FieldSymbols::ANY_FIELD] ?? [],
                    $precedingMandatoryDirectivesForDirectives[$directiveName] ?? []
                )
            ) {
                $allDirectives = array_merge(
                    $allDirectives,
                    $this->addMandatoryDirectivesForDirectives($mandatoryDirectivesForDirective)
                );
            }
            // Add the directive
            $allDirectives[] = $directive;
            // Add succeeding mandatory directives
            if (
                $mandatoryDirectivesForDirective = array_merge(
                    $succeedingMandatoryDirectivesForDirectives[FieldSymbols::ANY_FIELD] ?? [],
                    $succeedingMandatoryDirectivesForDirectives[$directiveName] ?? []
                )
            ) {
                $allDirectives = array_merge(
                    $allDirectives,
                    $this->addMandatoryDirectivesForDirectives($mandatoryDirectivesForDirective)
                );
            }
        }

        return $allDirectives;
    }

    /**
     * Execute a hook to allow to disable directives (eg: to implement a private schema)
     *
     * @param array<string,DirectiveResolverInterface[]> $directiveNameResolvers
     * @return array<string,DirectiveResolverInterface[]> $directiveNameResolvers
     */
    protected function filterDirectiveNameResolvers(array $directiveNameResolvers): array
    {
        // Execute a hook, allowing to filter them out (eg: removing fieldNames from a private schema)
        return array_filter(
            $directiveNameResolvers,
            function ($directiveName) use ($directiveNameResolvers) {
                $directiveResolvers = $directiveNameResolvers[$directiveName];
                foreach ($directiveResolvers as $directiveResolver) {
                    // Execute 2 filters: a generic one, and a specific one
                    if (
                        $this->hooksAPI->applyFilters(
                            HookHelpers::getHookNameToFilterDirective(),
                            true,
                            $this,
                            $directiveResolver,
                            $directiveName
                        )
                    ) {
                        return $this->hooksAPI->applyFilters(
                            HookHelpers::getHookNameToFilterDirective($directiveName),
                            true,
                            $this,
                            $directiveResolver,
                            $directiveName
                        );
                    }
                    return false;
                }
                return true;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Watch out! This function will be overridden for the UnionTypeResolver
     *
     * Collect all directives for all fields, and then build a single directive pipeline for all fields,
     * including all directives, even if they don't apply to all fields
     * Eg: id|title<skip>|excerpt<translate> will produce a pipeline [Skip, Translate] where they apply
     * to different fields. After producing the pipeline, add the mandatory items
     */
    public function enqueueFillingResultItemsFromIDs(array $ids_data_fields): void
    {
        $mandatoryDirectivesForFields = $this->getAllMandatoryDirectivesForFields();
        $mandatorySystemDirectives = $this->getMandatoryDirectives();
        foreach ($ids_data_fields as $id => $data_fields) {
            $fields = $this->getFieldsToEnqueueFillingResultItemsFromIDs($data_fields);
            $this->doEnqueueFillingResultItemsFromIDs($fields, $mandatoryDirectivesForFields, $mandatorySystemDirectives, $id, $data_fields);
        }
    }

    /**
     * Split function, so it can be invoked both from here and from the UnionTypeResolver
     */
    protected function getFieldsToEnqueueFillingResultItemsFromIDs(array $data_fields)
    {
        $fields = $data_fields['direct'];
        // Watch out: If there are conditional fields, these will be processed by this directive too
        // Hence, collect all these fields, and add them as if they were direct
        $conditionalFields = FieldHelpers::extractConditionalFields($data_fields);
        return array_unique(array_merge(
            $fields,
            $conditionalFields
        ));
    }

    /**
     * Split function, so it can be invoked both from here and from the UnionTypeResolver
     */
    public function doEnqueueFillingResultItemsFromIDs(array $fields, array $mandatoryDirectivesForFields, array $mandatorySystemDirectives, $id, array $data_fields)
    {
        $fieldDirectiveCounter = [];
        foreach ($fields as $field) {
            if (!isset($this->fieldDirectivesFromFieldCache[$field])) {
                // Get the directives from the field
                $directives = $this->fieldQueryInterpreter->getDirectives($field);

                // Add the mandatory directives defined for this field or for any field in this typeResolver
                $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
                if (
                    $mandatoryDirectivesForField = array_merge(
                        $mandatoryDirectivesForFields[FieldSymbols::ANY_FIELD] ?? [],
                        $mandatoryDirectivesForFields[$fieldName] ?? []
                    )
                ) {
                    // The mandatory directives must be placed first!
                    $directives = array_merge(
                        $mandatoryDirectivesForField,
                        $directives
                    );
                }

                // Place the mandatory "system" directives at the beginning of the list, then they will be added to their needed position in the pipeline
                $directives = array_merge(
                    $mandatorySystemDirectives,
                    $directives
                );
                // If the directives must be preceded by other directives, add them now
                $directives = $this->addMandatoryDirectivesForDirectives($directives);

                // Convert from directive to fieldDirective
                $fieldDirectives = implode(
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR,
                    array_map(
                        [$this->fieldQueryInterpreter, 'convertDirectiveToFieldDirective'],
                        $directives
                    )
                );
                // Assign in the cache
                $this->fieldDirectivesFromFieldCache[$field] = $fieldDirectives;
            }
            // Extract all the directives, and store which fields they process
            foreach (QueryHelpers::splitFieldDirectives($this->fieldDirectivesFromFieldCache[$field]) as $fieldDirective) {
                // Watch out! Directives can be repeated, and then they must be executed multiple times
                // Eg: resizing a pic to 25%: <resize(50%),resize(50%)>
                // However, because we are adding the $idsDataFields under key $fieldDirective, when the 2nd occurrence of the directive is found,
                // adding data would just override the previous entry, and we can't keep track that it's another iteration
                // Then, as solution, change the name of the $fieldDirective, adding "|counter". This is an artificial construction,
                // in which the "|" symbol could not be part of the field naturally
                if (isset($fieldDirectiveCounter[$field][(string)$id][$fieldDirective])) {
                    // Increase counter and add to $fieldDirective
                    $fieldDirective .= FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR . (++$fieldDirectiveCounter[$field][(string)$id][$fieldDirective]);
                } else {
                    $fieldDirectiveCounter[$field][(string)$id][$fieldDirective] = 0;
                }
                // Store which ID/field this directive must process
                if (in_array($field, $data_fields['direct'])) {
                    $this->fieldDirectiveIDFields[$fieldDirective][(string)$id]['direct'][] = $field;
                }
                if ($conditionalFields = $data_fields['conditional'][$field] ?? null) {
                    $this->fieldDirectiveIDFields[$fieldDirective][(string)$id]['conditional'][$field] = array_merge_recursive(
                        $this->fieldDirectiveIDFields[$fieldDirective][(string)$id]['conditional'][$field] ?? [],
                        $conditionalFields
                    );
                }
            }
        }
    }

    protected function processFillingResultItemsFromIDs(
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        // Iterate while there are directives with data to be processed
        while (!empty($this->fieldDirectiveIDFields)) {
            $fieldDirectiveIDFields = $this->fieldDirectiveIDFields;
            // Now that we have all data, remove all entries from the inner stack.
            // It may be filled again with composed directives, when resolving the pipeline
            $this->fieldDirectiveIDFields = [];

            // Calculate the fieldDirectives
            $fieldDirectives = array_keys($fieldDirectiveIDFields);

            // Calculate all the fields on which the directive will be applied.
            $fieldDirectiveFields = $fieldDirectiveFieldIDs = [];
            $fieldDirectiveDirectFields = [];
            foreach ($fieldDirectives as $fieldDirective) {
                foreach ($fieldDirectiveIDFields[$fieldDirective] as $id => $dataFields) {
                    $fieldDirectiveDirectFields = array_merge(
                        $fieldDirectiveDirectFields,
                        $dataFields['direct']
                    );
                    $conditionalFields = FieldHelpers::extractConditionalFields($dataFields);
                    $idFieldDirectiveIDFields = array_merge(
                        $dataFields['direct'],
                        $conditionalFields
                    );
                    $fieldDirectiveFields[$fieldDirective] = array_merge(
                        $fieldDirectiveFields[$fieldDirective] ?? [],
                        $idFieldDirectiveIDFields
                    );
                    // Also transpose the array to match field to IDs later on
                    foreach ($idFieldDirectiveIDFields as $field) {
                        $fieldDirectiveFieldIDs[$fieldDirective][$field][] = $id;
                    }
                }
                $fieldDirectiveFields[$fieldDirective] = array_unique($fieldDirectiveFields[$fieldDirective]);
            }
            $fieldDirectiveDirectFields = array_unique($fieldDirectiveDirectFields);
            $idFieldDirectiveIDFields = array_unique($idFieldDirectiveIDFields);

            // Validate and resolve the directives into instances and fields they operate on
            $directivePipelineSchemaErrors = [];
            $directivePipelineData = $this->resolveDirectivesIntoPipelineData(
                $fieldDirectives,
                $fieldDirectiveFields,
                false,
                $variables,
                $directivePipelineSchemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
                $schemaNotices,
                $schemaTraces
            );
            $schemaErrors = array_merge(
                $schemaErrors,
                $directivePipelineSchemaErrors
            );

            // If any directive failed validation and the field must be set to `null`,
            // then skip processing that field altogether
            $schemaErrorFailingFields = [];
            if (!empty($directivePipelineSchemaErrors)
                && ComponentConfiguration::removeFieldIfDirectiveFailed()
            ) {
                // Extract the failing fields from the path of the thrown error
                foreach ($directivePipelineSchemaErrors as $directivePipelineSchemaError) {
                    $schemaErrorFailingFields[] = $directivePipelineSchemaError[Tokens::PATH][0];
                }
                $schemaErrorFailingFields = array_unique($schemaErrorFailingFields);
                // Set those fields as null
                foreach ($fieldDirectives as $fieldDirective) {
                    foreach ($fieldDirectiveIDFields[$fieldDirective] as $id => $dataFields) {
                        $failingFields = array_intersect(
                            $dataFields['direct'],
                            $schemaErrorFailingFields
                        );
                        foreach ($failingFields as $field) {
                            $fieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($field);
                            $dbItems[(string)$id][$fieldOutputKey] = null;
                        }
                    }
                }
            }

            // From the fields, reconstitute the $idsDataFields for each directive,
            // and build the array to pass to the pipeline, for each directive (stage)
            $directiveResolverInstances = $pipelineIDsDataFields = [];
            foreach ($directivePipelineData as $pipelineStageData) {
                $directiveResolverInstance = $pipelineStageData['instance'];
                $fieldDirective = $pipelineStageData['fieldDirective'];
                $directiveFields = $pipelineStageData['fields'];
                // Only process the direct fields
                $directiveDirectFields = array_intersect(
                    $directiveFields,
                    $fieldDirectiveDirectFields
                );
                // Remove those fields which have a failing directive
                $directiveDirectFields = array_diff(
                    $directiveDirectFields,
                    $schemaErrorFailingFields
                );
                // From the fields, reconstitute the $idsDataFields for each directive, and build the array to pass to the pipeline, for each directive (stage)
                $directiveIDFields = [];
                foreach ($directiveDirectFields as $field) {
                    $ids = $fieldDirectiveFieldIDs[$fieldDirective][$field];
                    foreach ($ids as $id) {
                        $directiveIDFields[$id]['direct'][] = $field;
                        if ($fieldConditionalFields = $fieldDirectiveIDFields[$fieldDirective][$id]['conditional'][$field] ?? null) {
                            $directiveIDFields[$id]['conditional'][$field] = $fieldConditionalFields;
                        } else {
                            $directiveIDFields[$id]['conditional'] = $directiveIDFields[$id]['conditional'] ?? [];
                        }
                    }
                }
                $pipelineIDsDataFields[] = $directiveIDFields;
                $directiveResolverInstances[] = $directiveResolverInstance;
            }

            $directivePipelineSchemaErrors = $directivePipelineIDDBErrors = [];

            // We can finally resolve the pipeline, passing along an array with the ID and fields for each directive
            $directivePipeline = $this->getDirectivePipeline($directiveResolverInstances);
            $directivePipeline->resolveDirectivePipeline(
                $this,
                $pipelineIDsDataFields,
                $directiveResolverInstances,
                $resultIDItems,
                $unionDBKeyIDs,
                $dbItems,
                $previousDBItems,
                $variables,
                $messages,
                $directivePipelineIDDBErrors,
                $dbWarnings,
                $dbDeprecations,
                $dbNotices,
                $dbTraces,
                $directivePipelineSchemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
                $schemaNotices,
                $schemaTraces
            );

            // If any directive failed execution, then prepend the path on the error
            if ($directivePipelineSchemaErrors) {
                // Extract the failing fields from the path of the thrown error
                $failingFieldSchemaErrors = [];
                foreach ($directivePipelineSchemaErrors as $directivePipelineSchemaError) {
                    $schemaErrorFailingField = $directivePipelineSchemaError[Tokens::PATH][0];
                    if ($failingFields = $fieldDirectiveFields[$schemaErrorFailingField] ?? []) {
                        foreach ($failingFields as $failingField) {
                            $schemaError = $directivePipelineSchemaError;
                            array_unshift($schemaError[Tokens::PATH], $failingField);
                            $this->prependPathOnNestedErrors($schemaError, $failingField);
                            $failingFieldSchemaErrors[$failingField][] = $schemaError;
                        }
                    } else {
                        $schemaErrors[] = $directivePipelineSchemaError;
                    }
                }  
                foreach ($failingFieldSchemaErrors as $failingField => $failingSchemaErrors) {
                    $schemaErrors[] = [
                        Tokens::PATH => [$failingField],
                        Tokens::MESSAGE => $this->translationAPI->__('This field can\'t be executed due to errors from its directives', 'component-model'),
                        Tokens::EXTENSIONS => [
                            Tokens::NESTED => $failingSchemaErrors,
                        ],
                    ];
                }              
            }
            if ($directivePipelineIDDBErrors) {
                // Extract the failing fields from the path of the thrown error
                $failingFieldIDDBErrors = [];
                foreach ($directivePipelineIDDBErrors as $id => $directivePipelineDBErrors) {
                    foreach ($directivePipelineDBErrors as $directivePipelineDBError) {
                        $dbErrorFailingField = $directivePipelineDBError[Tokens::PATH][0];
                        if ($failingFields = $fieldDirectiveFields[$dbErrorFailingField] ?? []) {
                            foreach ($failingFields as $failingField) {
                                $dbError = $directivePipelineDBError;
                                array_unshift($dbError[Tokens::PATH], $failingField);
                                $this->prependPathOnNestedErrors($dbError, $failingField);
                                $failingFieldIDDBErrors[$failingField][$id][] = $dbError;
                            }
                        } else {
                            $dbErrors[$id][] = $directivePipelineDBError;
                        }
                    }
                }
                foreach ($failingFieldIDDBErrors as $failingField => $failingIDDBErrors) {
                    foreach ($failingIDDBErrors as $id => $failingDBErrors) {
                        $dbErrors[$id][] = [
                            Tokens::PATH => [$failingField],
                            Tokens::MESSAGE => $this->translationAPI->__('This field can\'t be executed due to errors from its directives', 'component-model'),
                            Tokens::EXTENSIONS => [
                                Tokens::NESTED => $failingDBErrors,
                            ],
                        ];
                    }
                }
            }
        }
    }

    protected function dissectFieldForSchema(string $field): ?array
    {
        if (!isset($this->dissectedFieldForSchemaCache[$field])) {
            $this->dissectedFieldForSchemaCache[$field] = $this->doDissectFieldForSchema($field);
        }
        return $this->dissectedFieldForSchemaCache[$field];
    }

    protected function doDissectFieldForSchema(string $field): ?array
    {
        return $this->fieldQueryInterpreter->extractFieldArgumentsForSchema($this, $field);
    }

    public function resolveSchemaValidationErrorDescriptions(string $field, array &$variables = null): array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        list(
            $validField,
            $fieldName,
            $fieldArgs,
            $schemaErrors,
        ) = $this->dissectFieldForSchema($field);
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            if ($maybeErrors = $fieldResolvers[0]->resolveSchemaValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
                foreach ($maybeErrors as $error) {
                    $schemaErrors[] = [
                        Tokens::PATH => [$field],
                        Tokens::MESSAGE => $error,
                    ];
                }
            }
            return $schemaErrors;
        }

        // If we reach here, no fieldResolver processes this field, which is an error
        /**
         * If the error happened from requesting a version that doesn't exist, show an appropriate error message
         */
        if (Environment::enableSemanticVersionConstraints()) {
            if ($versionConstraint = $fieldArgs[SchemaDefinition::ARGNAME_VERSION_CONSTRAINT] ?? null) {
                $errorMessage = sprintf(
                    $this->translationAPI->__(
                        'There is no resolver for field \'%s\' and version constraint \'%s\' on type \'%s\'',
                        'pop-component-model'
                    ),
                    $fieldName,
                    $versionConstraint,
                    $this->getMaybeNamespacedTypeName()
                );
            }
        }
        if (!isset($errorMessage)) {
            $errorMessage = sprintf(
                $this->translationAPI->__(
                    'There is no resolver for field \'%s\' on type \'%s\'',
                    'pop-component-model'
                ),
                $fieldName,
                $this->getMaybeNamespacedTypeName()
            );
        }
        return [
            [
                Tokens::PATH => [$field],
                Tokens::MESSAGE => $errorMessage,
            ],
        ];
    }

    public function resolveSchemaValidationWarningDescriptions(string $field, array &$variables = null): array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
                $fieldArgs,
                $schemaErrors,
                $schemaWarnings,
            ) = $this->dissectFieldForSchema($field);
            if ($maybeWarnings = $fieldResolvers[0]->resolveSchemaValidationWarningDescriptions($this, $fieldName, $fieldArgs)) {
                // $fieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($field);
                foreach ($maybeWarnings as $warning) {
                    $schemaWarnings[] = [
                        Tokens::PATH => [$field],
                        Tokens::MESSAGE => $warning,
                    ];
                }
            }
            return $schemaWarnings;
        }

        return [];
    }

    public function resolveSchemaDeprecationDescriptions(string $field, array &$variables = null): array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
                $fieldArgs,
                $schemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
            ) = $this->dissectFieldForSchema($field);
            $fieldSchemaDefinition = $fieldResolvers[0]->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
            if ($fieldSchemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] ?? null) {
                // $fieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($field);
                $schemaDeprecations[] = [
                    Tokens::PATH => [$field],
                    Tokens::MESSAGE => $fieldSchemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION],
                ];
            }
            // Check for deprecations in the enums
            if ($maybeDeprecations = $fieldResolvers[0]->resolveSchemaValidationDeprecationDescriptions($this, $fieldName, $fieldArgs)) {
                foreach ($maybeDeprecations as $deprecation) {
                    $schemaDeprecations[] = [
                        Tokens::PATH => [$field],
                        Tokens::MESSAGE => $deprecation,
                    ];
                }
            }
            return $schemaDeprecations;
        }

        return [];
    }

    public function getSchemaFieldArgs(string $field): array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
            $fieldArgs = $this->fieldQueryInterpreter->extractStaticFieldArguments($field);
            $fieldSchemaDefinition = $fieldResolvers[0]->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
            return $fieldSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? [];
        }

        return [];
    }

    public function enableOrderedSchemaFieldArgs(string $field): bool
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
            return $fieldResolvers[0]->enableOrderedSchemaFieldArgs($this, $fieldName);
        }

        return false;
    }

    public function resolveFieldTypeResolverClass(string $field): ?string
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
            ) = $this->dissectFieldForSchema($field);
            return $fieldResolvers[0]->resolveFieldTypeResolverClass($this, $fieldName);
        }

        return null;
    }

    /**
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        object $resultItem,
        string $field,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        // Get the value from a fieldResolver, from the first one who can deliver the value
        // (The fact that they resolve the fieldName doesn't mean that they will always resolve it for that specific $resultItem)
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            // Important: $validField becomes $field: remove all invalid fieldArgs before executing `resolveValue` on the fieldResolver
            list(
                $field,
                $fieldName,
                $fieldArgs,
                $schemaErrors,
                $schemaWarnings,
            ) = $this->dissectFieldForSchema($field);

            // Store the warnings to be read if needed
            if ($schemaWarnings) {
                $this->feedbackMessageStore->addSchemaWarnings($schemaWarnings);
            }
            if ($schemaErrors) {
                return $this->errorProvider->getNestedSchemaErrorsFieldError($schemaErrors, $fieldName);
            }

            // Important: calculate 'isAnyFieldArgumentValueDynamic' before resolving the args for the resultItem
            // That is because if when resolving there is an error, the fieldArgValue will be removed completely, then we don't know that we must validate the schema again
            // Eg: doing /?query=arrayUnique(extract(..., 0)) and extract fails, arrayUnique will have no fieldArgs. However its fieldArg is mandatory, but by then it doesn't know it needs to validate it
            // Before resolving the fieldArgValues which are fields:
            // Calculate $validateSchemaOnResultItem: if any value containes a field, then we must perform the schemaValidation on the item, such as checking that all mandatory fields are there
            // For instance: After resolving a field and being casted it may be incorrect, so the value is invalidated, and after the schemaValidation the proper error is shown
            // Also need to check for variables, since these must be resolved too
            // For instance: ?query=posts(limit:3),post(id:$id).id|title&id=112
            // We can also force it through an option. This is needed when the field is created on runtime.
            // Eg: through the <transform> directive, in which case no parameter is dynamic anymore by the time it reaches here, yet it was not validated statically either
            $validateSchemaOnResultItem =
                ($options[self::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM] ?? null) ||
                FieldQueryUtils::isAnyFieldArgumentValueDynamic(
                    array_values(
                        $this->fieldQueryInterpreter->extractFieldArguments($this, $field)
                    )
                );

            // Once again, the $validField becomes the $field
            list(
                $field,
                $fieldName,
                $fieldArgs,
                $dbErrors,
                $dbWarnings
            ) = $this->fieldQueryInterpreter->extractFieldArgumentsForResultItem($this, $resultItem, $field, $variables, $expressions);

            // Store the warnings to be read if needed
            if ($dbWarnings) {
                $this->feedbackMessageStore->addDBWarnings($dbWarnings);
            }
            if ($dbErrors) {
                return $this->errorProvider->getNestedDBErrorsFieldError($dbErrors, $fieldName);
            }

            foreach ($fieldResolvers as $fieldResolver) {
                // Also send the typeResolver along, as to get the id of the $resultItem being passed
                if ($fieldResolver->resolveCanProcessResultItem($this, $resultItem, $fieldName, $fieldArgs)) {
                    if ($validateSchemaOnResultItem) {
                        if ($maybeErrors = $fieldResolver->resolveSchemaValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
                            return $this->errorProvider->getValidationFailedError($fieldName, $fieldArgs, $maybeErrors);
                        }
                        if ($maybeDeprecations = $fieldResolver->resolveSchemaValidationDeprecationDescriptions($this, $fieldName, $fieldArgs)) {
                            $id = $this->getID($resultItem);
                            foreach ($maybeDeprecations as $deprecation) {
                                $dbDeprecations[(string)$id][] = [
                                    Tokens::PATH => [$field],
                                    Tokens::MESSAGE => $deprecation,
                                ];
                            }
                            $this->feedbackMessageStore->addDBDeprecations($dbDeprecations);
                        }
                    }
                    if ($validationErrorDescriptions = $fieldResolver->getValidationErrorDescriptions($this, $resultItem, $fieldName, $fieldArgs)) {
                        return $this->errorProvider->getValidationFailedError($fieldName, $fieldArgs, $validationErrorDescriptions);
                    }
                    
                    // Resolve the value
                    $value = $fieldResolver->resolveValue($this, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);

                    /**
                     * Validate that the value is what was defined in the schema, or throw a corresponding error.
                     * 
                     * Items being validated:
                     * 
                     * - Is it null?
                     * - Is it an array when it should be?
                     * - Is it not an array when it should not be?
                     * 
                     * Items NOT being validated:
                     * 
                     * - Is the returned type (String, Int, some Object, etc) the expected one?
                     * 
                     * According to the GraphQL speck, checking if a non-null field returns null
                     * is handled always:
                     * 
                     *   If the result of resolving a field is null (either because the function
                     *   to resolve the field returned null or because a field error was raised),
                     *   and that field is of a Non-Null type, then a field error is raised.
                     *   The error must be added to the "errors" list in the response.
                     * 
                     * @see https://spec.graphql.org/draft/#sec-Handling-Field-Errors
                     * 
                     * All other conditions, check them when enabled by configuration.
                     */
                    if ($value === null) {
                        $fieldSchemaDefinition = $fieldResolver->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
                        if ($fieldSchemaDefinition[SchemaDefinition::ARGNAME_NON_NULLABLE] ?? false) {
                            return $this->errorProvider->getNonNullableFieldError($fieldName);
                        }
                    } elseif (ComponentConfiguration::validateFieldTypeResponseWithSchemaDefinition()) {
                        $fieldSchemaDefinition = $fieldResolver->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
                        // If may be array or not, then there's no validation to do
                        $fieldType = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE];
                        $fieldMayBeArrayType = in_array($fieldType, [
                            SchemaDefinition::TYPE_INPUT_OBJECT,
                            SchemaDefinition::TYPE_OBJECT,
                            SchemaDefinition::TYPE_MIXED,
                        ]);
                        if (!$fieldMayBeArrayType) {
                            $fieldIsArrayType = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] ?? false;
                            if (is_array($value) && !$fieldIsArrayType) {
                                return $this->errorProvider->getMustNotBeArrayFieldError($fieldName, $value);
                            }
                            if (!is_array($value) && $fieldIsArrayType) {
                                return $this->errorProvider->getMustBeArrayFieldError($fieldName, $value);
                            }
                            $fieldIsNonEmptyArrayType = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_NON_EMPTY_ARRAY] ?? false;
                            if ($value === [] && $fieldIsNonEmptyArrayType) {
                                return $this->errorProvider->getMustNotBeEmptyArrayFieldError($fieldName, $value);
                            }
                        }
                    }

                    // Everything is good, return the value (which could also be an Error!)
                    return $value;
                }
            }
            return $this->errorProvider->getNoFieldResolverProcessesFieldError($this->getID($resultItem), $fieldName, $fieldArgs);
        }

        // Return an error to indicate that no fieldResolver processes this field, which is different than returning a null value.
        // Needed for compatibility with CustomPostUnionTypeResolver (so that data-fields aimed for another post_type are not retrieved)
        $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
        return $this->errorProvider->getNoFieldError($this->getID($resultItem), $fieldName, $this->getMaybeNamespacedTypeName());
    }

    protected function processFlatShapeSchemaDefinition(array $options = [])
    {
        $typeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($this);

        // By now, we have the schema definition
        if (isset($this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS])) {
            $connections = &$this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS];
            foreach ($connections as &$connection) {
                // If it is a recursion or repeated there will be no schema
                if (isset($connection[SchemaDefinition::ARGNAME_TYPE_SCHEMA])) {
                    // Remove the typeSchema entry
                    unset($connection[SchemaDefinition::ARGNAME_TYPE_SCHEMA]);
                }
            }
        }
    }

    public function getSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = []): array
    {
        $typeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($this);

        // Stop recursion
        $class = get_called_class();
        if (in_array($class, $stackMessages['processed'])) {
            return [
                $typeSchemaKey => [
                    SchemaDefinition::ARGNAME_RECURSION => true,
                ]
            ];
        }

        $isFlatShape = isset($options['shape']) && $options['shape'] == SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_FLAT;

        // If "compressed" or printing a flat shape, and the resolver has already been added to the schema, then skip it
        if (($isFlatShape || ($options['compressed'] ?? null)) && in_array($class, $generalMessages['processed'])) {
            return [
                $typeSchemaKey => [
                    SchemaDefinition::ARGNAME_REPEATED => true,
                ]
            ];
        }

        $stackMessages['processed'][] = $class;
        $generalMessages['processed'][] = $class;
        if (is_null($this->schemaDefinition)) {
            // Important: This line stops the recursion when a type reference each other circularly, so do not remove it!
            $this->schemaDefinition = [];
            $this->addSchemaDefinition($stackMessages, $generalMessages, $options);
            // If it is a flat shape, we can remove the nested connections, replace them only with the type name
            if ($isFlatShape) {
                $this->processFlatShapeSchemaDefinition($options);
                // Add the type to the list of all types, displayed when doing "shape=>flat"
                $generalMessages[SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey] = $this->schemaDefinition[$typeSchemaKey];
            }
        }

        return $this->schemaDefinition;
    }

    protected function getDirectiveSchemaDefinition(DirectiveResolverInterface $directiveResolver, array $options = []): array
    {
        $directiveSchemaDefinition = $directiveResolver->getSchemaDefinitionForDirective($this);
        return $directiveSchemaDefinition;
    }

    protected function addSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = [])
    {
        $typeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($this);
        $typeName = $this->getMaybeNamespacedTypeName();
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_NAME] = $typeName;
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_NAMESPACED_NAME] = $this->getNamespacedTypeName();
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_ELEMENT_NAME] = $this->getTypeName();

        // Properties
        if ($description = $this->getSchemaTypeDescription()) {
            $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
        }

        // Add the directives (non-global)
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->getSchemaDirectiveResolvers(false);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_DIRECTIVES][$directiveName] = $this->getDirectiveSchemaDefinition($directiveResolver, $options);
        }

        // Add the fields (non-global)
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_FIELDS] = [];
        $schemaFieldResolvers = $this->getSchemaFieldResolvers(false);
        foreach ($schemaFieldResolvers as $fieldName => $fieldResolver) {
            $this->addFieldSchemaDefinition($fieldResolver, $fieldName, $stackMessages, $generalMessages, $options);
        }

        // Add all the implemented interfaces
        $typeInterfaceDefinitions = [];
        foreach ($this->getAllImplementedInterfaceResolverInstances() as $interfaceInstance) {
            $interfaceSchemaKey = $this->schemaDefinitionService->getInterfaceSchemaKey($interfaceInstance);

            // Conveniently get the fields from the schema, which have already been calculated above
            // since they also include their interface fields
            $interfaceFieldNames = $interfaceInstance->getFieldNamesToImplement();
            // The Interface fields may be implemented as either FieldResolver fields or FieldResolver connections,
            // Eg: Interface "Elemental" has field "id" and connection "self"
            // Merge both cases into interface fields
            $interfaceFields = array_filter(
                $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_FIELDS],
                function ($fieldName) use ($interfaceFieldNames) {
                    return in_array($fieldName, $interfaceFieldNames);
                },
                ARRAY_FILTER_USE_KEY
            );
            $interfaceConnections = array_filter(
                $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS],
                function ($connectionName) use ($interfaceFieldNames) {
                    return in_array($connectionName, $interfaceFieldNames);
                },
                ARRAY_FILTER_USE_KEY
            );
            $interfaceFields = array_merge(
                $interfaceFields,
                $interfaceConnections
            );
            // Interfaces and FieldResolvers must match on all attributes of the signature:
            // fieldName, arguments, and return type. But not on the description of the field,
            // as to make it more specific for the field
            // So override the description with the interface's own
            foreach ($interfaceFieldNames as $interfaceFieldName) {
                // Make sure a definition for that fieldName has been added,
                // since the field could've been removed through an ACL
                if ($interfaceFields[$interfaceFieldName]) {
                    if ($description = $interfaceInstance->getSchemaFieldDescription($interfaceFieldName)) {
                        $interfaceFields[$interfaceFieldName][SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
                    } else {
                        // Do not keep the description from the fieldResolver
                        unset($interfaceFields[$interfaceFieldName][SchemaDefinition::ARGNAME_DESCRIPTION]);
                    }
                }
            }
            // An interface can itself implement interfaces!
            $interfaceImplementedInterfaceNames = [];
            if ($interfaceImplementedInterfaceClasses = $interfaceInstance->getImplementedFieldInterfaceResolverClasses()) {
                foreach ($interfaceImplementedInterfaceClasses as $interfaceImplementedInterfaceClass) {
                    $interfaceImplementedInterfaceInstance = $this->instanceManager->getInstance($interfaceImplementedInterfaceClass);
                    $interfaceImplementedInterfaceNames[] = $interfaceImplementedInterfaceInstance->getMaybeNamespacedInterfaceName();
                }
            }
            // // Add the versions to the fields, as coming from the interface
            // $interfaceFields = array_map(
            //     function ($fieldSchemaDefinition) use ($interfaceInstance) {
            //         if ($version = $interfaceInstance->getSchemaInterfaceVersion($fieldSchemaDefinition[SchemaDefinition::ARGNAME_NAME])) {
            //             $fieldSchemaDefinition[SchemaDefinition::ARGNAME_VERSION] = $version;
            //         }
            //         return $fieldSchemaDefinition;
            //     },
            //     $interfaceFields
            // );
            $interfaceName = $interfaceInstance->getMaybeNamespacedInterfaceName();
            // Possible types: Because we are generating this list as we go along resolving all the types, simply have this value point to a reference in $generalMessages
            // Just by updating that variable, it will eventually be updated everywhere
            $generalMessages['interfaceGeneralTypes'][$interfaceName] = $generalMessages['interfaceGeneralTypes'][$interfaceName] ?? [];
            $interfacePossibleTypes = &$generalMessages['interfaceGeneralTypes'][$interfaceName];
            // Add this type to the list of implemented types for this interface
            $interfacePossibleTypes[] = $typeName;
            $typeInterfaceDefinitions[$interfaceSchemaKey] = [
                SchemaDefinition::ARGNAME_NAME => $interfaceName,
                SchemaDefinition::ARGNAME_NAMESPACED_NAME => $interfaceInstance->getNamespacedInterfaceName(),
                SchemaDefinition::ARGNAME_ELEMENT_NAME => $interfaceInstance->getInterfaceName(),
                SchemaDefinition::ARGNAME_DESCRIPTION => $interfaceInstance->getSchemaInterfaceDescription(),
                SchemaDefinition::ARGNAME_FIELDS => $interfaceFields,
                SchemaDefinition::ARGNAME_INTERFACES => $interfaceImplementedInterfaceNames,
                // The list of types that implement this interface
                SchemaDefinition::ARGNAME_POSSIBLE_TYPES => &$interfacePossibleTypes,
            ];
        }
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_INTERFACES] = $typeInterfaceDefinitions;
    }

    protected function getSchemaDirectiveResolvers(bool $global): array
    {
        $directiveResolverInstances = [];
        $directiveNameResolvers = $this->getDirectiveNameResolvers();
        foreach ($directiveNameResolvers as $directiveName => $directiveResolvers) {
            foreach ($directiveResolvers as $directiveResolver) {
                // A directive can decide to not be added to the schema, eg: when it is repeated/implemented several times
                if ($directiveResolver->skipAddingToSchemaDefinition()) {
                    continue;
                }
                $isGlobal = $directiveResolver->isGlobal($this);
                if (($global && $isGlobal) || (!$global && !$isGlobal)) {
                    $directiveResolverInstances[$directiveName] = $directiveResolver;
                }
            }
        }
        return $directiveResolverInstances;
    }

    protected function getSchemaFieldResolvers(bool $global): array
    {
        $schemaFieldResolvers = [];
        foreach ($this->getAllFieldResolvers() as $fieldName => $fieldResolvers) {
            // Get the documentation from the first element
            $fieldResolver = $fieldResolvers[0];
            $isGlobal = $fieldResolver->isGlobal($this, $fieldName);
            if (($global && $isGlobal) || (!$global && !$isGlobal)) {
                $schemaFieldResolvers[$fieldName] =  $fieldResolver;
            }
        }
        return $schemaFieldResolvers;
    }

    protected function addFieldSchemaDefinition(FieldResolverInterface $fieldResolver, string $fieldName, array $stackMessages, array &$generalMessages, array $options = [])
    {
        /**
         * Fields may not be directly visible in the schema
         */
        if ($fieldResolver->skipAddingToSchemaDefinition($this, $fieldName)) {
            return;
        }

        // Watch out! We are passing empty $fieldArgs to generate the schema!
        $fieldSchemaDefinition = $fieldResolver->getSchemaDefinitionForField($this, $fieldName, []);
        // Add subfield schema if it is deep, and this typeResolver has not been processed yet
        if ($options['deep'] ?? null) {
            // If this field is relational, then add its own schema
            if ($fieldTypeResolverClass = $this->resolveFieldTypeResolverClass($fieldName)) {
                $fieldTypeResolver = $this->instanceManager->getInstance($fieldTypeResolverClass);
                $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE_SCHEMA] = $fieldTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, $options);
            }
        }
        // Convert the field type from its internal representation (eg: "array:id") to the type (eg: "array:Post")
        if ($options['useTypeName'] ?? null) {
            // The type is mandatory. If not provided, use the default one
            $type = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE] ?? $this->schemaDefinitionService->getDefaultType();
            $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE] = SchemaHelpers::convertTypeIDToTypeName($type, $this, $fieldName);
        } else {
            // Display the type under entry "referencedType"
            if ($types = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE_SCHEMA] ?? null) {
                $typeNames = array_keys($types);
                $fieldSchemaDefinition[SchemaDefinition::ARGNAME_REFERENCED_TYPE] = $typeNames[0];
            }
        }
        $isGlobal = $fieldResolver->isGlobal($this, $fieldName);
        $isConnection = isset($fieldSchemaDefinition[SchemaDefinition::ARGNAME_RELATIONAL]) && $fieldSchemaDefinition[SchemaDefinition::ARGNAME_RELATIONAL];
        if ($isGlobal) {
            // If it is relational, it is a global connection
            if ($isConnection) {
                $entry = SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS;
                // Remove the "types"
                if ($options['useTypeName'] ?? null) {
                    unset($fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE_SCHEMA]);
                }
            } else {
                $entry = SchemaDefinition::ARGNAME_GLOBAL_FIELDS;
            }
        } else {
            // Split the results into "fields" and "connections"
            $entry = $isConnection ?
                SchemaDefinition::ARGNAME_CONNECTIONS :
                SchemaDefinition::ARGNAME_FIELDS;
        }
        // Can remove attribute "relational"
        if ($isConnection) {
            unset($fieldSchemaDefinition[SchemaDefinition::ARGNAME_RELATIONAL]);
        }
        $typeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($this);
        $this->schemaDefinition[$typeSchemaKey][$entry][$fieldName] = $fieldSchemaDefinition;
    }

    protected function isFieldNameResolvedByFieldResolver(FieldResolverInterface $fieldResolver, string $fieldName, array $fieldInterfaceResolverClasses): bool
    {
        // Calculate all the interfaces that define this fieldName
        $fieldInterfaceResolverClassesForField = array_values(array_filter(
            $fieldInterfaceResolverClasses,
            function ($fieldInterfaceResolverClass) use ($fieldName): bool {
                /** @var FieldInterfaceResolverInterface */
                $fieldInterfaceResolver = $this->instanceManager->getInstance($fieldInterfaceResolverClass);
                return in_array($fieldName, $fieldInterfaceResolver->getFieldNamesToImplement());
            }
        ));
        // Execute 2 filters: a generic one, and a specific one
        if (
            $this->hooksAPI->applyFilters(
                HookHelpers::getHookNameToFilterField(),
                true,
                $this,
                $fieldResolver,
                $fieldInterfaceResolverClassesForField,
                $fieldName
            )
        ) {
            return $this->hooksAPI->applyFilters(
                HookHelpers::getHookNameToFilterField($fieldName),
                true,
                $this,
                $fieldResolver,
                $fieldInterfaceResolverClassesForField,
                $fieldName
            );
        }
        return false;
    }

    /**
     * Return the fieldNames resolved by the fieldResolverClass, adding a hook to disable each of them (eg: to implement a private schema)
     *
     * @return string[]
     */
    protected function getFieldNamesResolvedByFieldResolver(FieldResolverInterface $fieldResolver): array
    {
        $fieldResolverClass = get_class($fieldResolver);
        if (!isset($this->fieldNamesResolvedByFieldResolver[$fieldResolverClass])) {
            // Merge the fieldNames resolved by this field resolver class, and the interfaces it implements
            $fieldNames = array_merge(
                $fieldResolver->getFieldNamesToResolve(),
                $fieldResolver->getFieldNamesFromInterfaces()
            );

            // Enable to exclude fieldNames, so they are not added to the schema.
            $excludedFieldNames = [];
            // Whenever:
            // 1. Exclude the admin fields, if "Admin" Schema is not enabled
            if (!ComponentConfiguration::enableAdminSchema()) {
                $excludedFieldNames = $fieldResolver->getAdminFieldNames();
            }
            // 2. By filter hook
            $excludedFieldNames = $this->hooksAPI->applyFilters(
                Hooks::EXCLUDE_FIELDNAMES,
                $excludedFieldNames,
                $fieldResolver,
                $fieldNames
            );
            if ($excludedFieldNames !== []) {
                $fieldNames = array_values(array_diff(
                    $fieldNames,
                    $excludedFieldNames
                ));
            }

            // Execute a hook, allowing to filter them out (eg: removing fieldNames from a private schema)
            // Also pass the implemented interfaces defining the field
            $fieldInterfaceResolverClasses = $fieldResolver->getImplementedFieldInterfaceResolverClasses();
            $fieldNames = array_filter(
                $fieldNames,
                fn ($fieldName) => $this->isFieldNameResolvedByFieldResolver($fieldResolver, $fieldName, $fieldInterfaceResolverClasses)
            );
            $this->fieldNamesResolvedByFieldResolver[$fieldResolverClass] = $fieldNames;
        }
        return $this->fieldNamesResolvedByFieldResolver[$fieldResolverClass];
    }

    protected function getAllFieldResolvers(): array
    {
        if (is_null($this->schemaFieldResolvers)) {
            $this->schemaFieldResolvers = $this->calculateAllFieldResolvers();
        }
        return $this->schemaFieldResolvers;
    }

    protected function getTypeResolverClassToCalculateSchema(): string
    {
        return get_called_class();
    }

    protected function calculateAllFieldResolvers(): array
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        $schemaFieldResolvers = [];

        // Get the fieldResolvers attached to this typeResolver and to all the interfaces it implements
        $classStack = [
            $this->getTypeResolverClassToCalculateSchema(),
        ];
        while (!empty($classStack)) {
            $class = array_shift($classStack);
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                /** @var FieldResolverInterface[] */
                $attachedFieldResolvers = $attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::FIELDRESOLVERS);
                foreach ($attachedFieldResolvers as $fieldResolver) {
                    // Process the fields which have not been processed yet
                    $extensionFieldNames = $this->getFieldNamesResolvedByFieldResolver($fieldResolver);
                    foreach (array_diff($extensionFieldNames, array_keys($schemaFieldResolvers)) as $fieldName) {
                        // Watch out here: no fieldArgs!!!! So this deals with the base case (static), not with all cases (runtime)
                        // If using an ACL to remove a field from an interface,
                        // getting the fieldResolvers for that field will be empty
                        // Then ignore adding the field, it must not be added to the schema
                        if ($fieldResolversForField = $this->getFieldResolversForField($fieldName)) {
                            $schemaFieldResolvers[$fieldName] = $fieldResolversForField;
                        }
                    }
                    // The interfaces implemented by the FieldResolver can have, themselves, fieldResolvers attached to them
                    $classStack = array_values(array_unique(array_merge(
                        $classStack,
                        $fieldResolver->getImplementedFieldInterfaceResolverClasses()
                    )));
                }
                // Otherwise, continue iterating for the class parents
            } while ($class = get_parent_class($class));
        }

        return $schemaFieldResolvers;
    }

    public function getAllMandatoryDirectivesForFields(): array
    {
        if (is_null($this->mandatoryDirectivesForFields)) {
            $this->mandatoryDirectivesForFields = $this->calculateAllMandatoryDirectivesForFields();
        }
        return $this->mandatoryDirectivesForFields;
    }

    protected function calculateAllMandatoryDirectivesForFields(): array
    {
        $mandatoryDirectivesForFields = [];
        $typeResolverDecorators = $this->getAllTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            // array_merge_recursive so that if 2 different decorators add a directive for the same field, the results are merged together, not override each other
            if ($typeResolverDecorator->enabled($this)) {
                $mandatoryDirectivesForFields = array_merge_recursive(
                    $mandatoryDirectivesForFields,
                    $typeResolverDecorator->getMandatoryDirectivesForFields($this)
                );
            }
        }
        return $mandatoryDirectivesForFields;
    }

    protected function getAllPrecedingMandatoryDirectivesForDirectives(): array
    {
        if (is_null($this->precedingMandatoryDirectivesForDirectives)) {
            $this->precedingMandatoryDirectivesForDirectives = $this->calculateAllPrecedingMandatoryDirectivesForDirectives();
        }
        return $this->precedingMandatoryDirectivesForDirectives;
    }

    protected function calculateAllPrecedingMandatoryDirectivesForDirectives(): array
    {
        $precedingMandatoryDirectivesForDirectives = [];
        $typeResolverDecorators = $this->getAllTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            // array_merge_recursive so that if 2 different decorators add a directive for the same directive, the results are merged together, not override each other
            if ($typeResolverDecorator->enabled($this)) {
                $precedingMandatoryDirectivesForDirectives = array_merge_recursive(
                    $precedingMandatoryDirectivesForDirectives,
                    $typeResolverDecorator->getPrecedingMandatoryDirectivesForDirectives($this)
                );
            }
        }

        return $precedingMandatoryDirectivesForDirectives;
    }

    protected function getAllSucceedingMandatoryDirectivesForDirectives(): array
    {
        if (is_null($this->succeedingMandatoryDirectivesForDirectives)) {
            $this->succeedingMandatoryDirectivesForDirectives = $this->calculateAllSucceedingMandatoryDirectivesForDirectives();
        }
        return $this->succeedingMandatoryDirectivesForDirectives;
    }

    protected function calculateAllSucceedingMandatoryDirectivesForDirectives(): array
    {
        $succeedingMandatoryDirectivesForDirectives = [];
        $typeResolverDecorators = $this->getAllTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            // array_merge_recursive so that if 2 different decorators add a directive for the same directive, the results are merged together, not override each other
            if ($typeResolverDecorator->enabled($this)) {
                $succeedingMandatoryDirectivesForDirectives = array_merge_recursive(
                    $succeedingMandatoryDirectivesForDirectives,
                    $typeResolverDecorator->getSucceedingMandatoryDirectivesForDirectives($this)
                );
            }
        }

        return $succeedingMandatoryDirectivesForDirectives;
    }

    protected function getAllTypeResolverDecorators(): array
    {
        if (is_null($this->typeResolverDecorators)) {
            $this->typeResolverDecorators = $this->calculateAllTypeResolverDecorators();
        }
        return $this->typeResolverDecorators;
    }

    protected function calculateAllTypeResolverDecorators(): array
    {
        $typeResolverDecorators = [];
        /**
         * Also get the decorators for the implemented interfaces
         */
        $classes = array_merge(
            [
                $this->getTypeResolverClassToCalculateSchema(),
            ],
            $this->getAllImplementedInterfaceClasses()
        );
        foreach ($classes as $class) {
            $typeResolverDecorators = array_merge(
                $typeResolverDecorators,
                $this->calculateAllTypeResolverDecoratorsForTypeOrInterfaceClass($class)
            );
        }

        return $typeResolverDecorators;
    }

    /**
     * @return TypeResolverDecoratorInterface[]
     */
    protected function calculateAllTypeResolverDecoratorsForTypeOrInterfaceClass(string $class): array
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        $typeResolverDecorators = [];

        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        do {
            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            /** @var TypeResolverDecoratorInterface[] */
            $attachedTypeResolverDecorators = array_reverse($attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::TYPERESOLVERDECORATORS));
            // Order them by priority: higher priority are evaluated first
            $extensionPriorities = array_map(
                fn (TypeResolverDecoratorInterface $typeResolverDecorator) => $typeResolverDecorator->getPriorityToAttachToClasses(),
                $attachedTypeResolverDecorators
            );
            array_multisort($extensionPriorities, SORT_DESC, SORT_NUMERIC, $attachedTypeResolverDecorators);
            // Add them to the results
            $typeResolverDecorators = array_merge(
                $typeResolverDecorators,
                $attachedTypeResolverDecorators
            );
            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        return $typeResolverDecorators;
    }

    /**
     * @return FieldInterfaceResolverInterface[]
     */
    public function getAllImplementedInterfaceResolverInstances(): array
    {
        if (is_null($this->interfaceResolverInstances)) {
            $this->interfaceResolverInstances = $this->calculateAllImplementedInterfaceResolverInstances();
        }
        return $this->interfaceResolverInstances;
    }

    protected function calculateAllImplementedInterfaceResolverInstances(): array
    {
        return array_map(
            function ($interfaceClass) {
                return $this->instanceManager->getInstance($interfaceClass);
            },
            $this->getAllImplementedInterfaceClasses()
        );
    }

    public function getAllImplementedInterfaceClasses(): array
    {
        if (is_null($this->interfaceClasses)) {
            $this->interfaceClasses = $this->calculateAllImplementedInterfaceClasses();
        }
        return $this->interfaceClasses;
    }

    protected function calculateAllImplementedInterfaceClasses(): array
    {
        $interfaceClasses = [];
        $processedFieldResolverClasses = [];

        foreach ($this->getAllFieldResolvers() as $fieldName => $fieldResolvers) {
            foreach ($fieldResolvers as $fieldResolver) {
                $fieldResolverClass = get_class($fieldResolver);
                if (!in_array($fieldResolverClass, $processedFieldResolverClasses)) {
                    $processedFieldResolverClasses[] = $fieldResolverClass;
                    $interfaceClasses = array_merge(
                        $interfaceClasses,
                        $fieldResolver->getImplementedFieldInterfaceResolverClasses()
                    );
                }
            }
        }

        return array_values(array_unique($interfaceClasses));
    }

    /**
     * @return FieldResolverInterface[]
     */
    protected function getFieldResolversForField(string $field): array
    {
        // Calculate the fieldResolver to process this field if not already in the cache
        // If none is found, this value will be set to NULL. This is needed to stop attempting to find the fieldResolver
        if (!isset($this->fieldResolvers[$field])) {
            $this->fieldResolvers[$field] = $this->calculateFieldResolversForField($field);
        }

        return $this->fieldResolvers[$field];
    }

    public function hasFieldResolversForField(string $field): bool
    {
        return !empty($this->getFieldResolversForField($field));
    }

    protected function calculateFieldResolversForField(string $field): array
    {
        // Important: here we CAN'T use `dissectFieldForSchema` to get the fieldArgs, because it will attempt to validate them
        // To validate them, the fieldQueryInterpreter needs to know the schema, so it once again calls functions from this typeResolver
        // Generating an infinite loop
        // Then, just to find out which fieldResolvers will process this field, crudely obtain the fieldArgs, with NO schema-based validation!
        // list(
        //     $field,
        //     $fieldName,
        //     $fieldArgs,
        // ) = $this->dissectFieldForSchema($field);
        $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
        $fieldArgs = $this->fieldQueryInterpreter->extractStaticFieldArguments($field);

        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        $fieldResolvers = [];
        // Get the fieldResolvers attached to this typeResolver and to all the interfaces it implements
        $classStack = [
            $this->getTypeResolverClassToCalculateSchema(),
        ];
        while (!empty($classStack)) {
            $class = array_shift($classStack);
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                // All the Units and their priorities for this class level
                $classTypeResolverPriorities = [];
                $classFieldResolvers = [];

                // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
                /** @var FieldResolverInterface[] */
                $attachedFieldResolvers = array_reverse($attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::FIELDRESOLVERS));
                foreach ($attachedFieldResolvers as $fieldResolver) {
                    $extensionFieldNames = $this->getFieldNamesResolvedByFieldResolver($fieldResolver);
                    if (in_array($fieldName, $extensionFieldNames)) {
                        // Check that the fieldResolver can handle the field based on other parameters (eg: "version" in the fieldArgs)
                        if ($fieldResolver->resolveCanProcess($this, $fieldName, $fieldArgs)) {
                            $extensionPriority = $fieldResolver->getPriorityToAttachToClasses();
                            $classTypeResolverPriorities[] = $extensionPriority;
                            $classFieldResolvers[] = $fieldResolver;
                        }
                    }
                    // The interfaces implemented by the FieldResolver can have, themselves, fieldResolvers attached to them
                    $classStack = array_values(array_unique(array_merge(
                        $classStack,
                        $fieldResolver->getImplementedFieldInterfaceResolverClasses()
                    )));
                }
                // Sort the found units by their priority, and then add to the stack of all units, for all classes
                // Higher priority means they execute first!
                array_multisort($classTypeResolverPriorities, SORT_DESC, SORT_NUMERIC, $classFieldResolvers);
                $fieldResolvers = array_merge(
                    $fieldResolvers,
                    $classFieldResolvers
                );
                // Continue iterating for the class parents
            } while ($class = get_parent_class($class));
        }

        // Return all the units that resolve the fieldName
        return $fieldResolvers;
    }

    protected function calculateFieldDirectiveNameResolvers(): array
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        $directiveNameResolvers = [];

        // Directives can also be attached to the interface implemented by this typeResolver
        $classes = array_merge(
            [
                $this->getTypeResolverClassToCalculateSchema(),
            ],
            $this->getAllImplementedInterfaceClasses()
        );
        foreach ($classes as $class) {
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
                /** @var DirectiveResolverInterface[] */
                $attachedDirectiveResolvers = array_reverse($attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::DIRECTIVERESOLVERS));
                // Order them by priority: higher priority are evaluated first
                $extensionPriorities = array_map(
                    fn (DirectiveResolverInterface $directiveResolver) => $directiveResolver->getPriorityToAttachToClasses(),
                    $attachedDirectiveResolvers
                );
                array_multisort($extensionPriorities, SORT_DESC, SORT_NUMERIC, $attachedDirectiveResolvers);
                // Add them to the results. We keep the list of all resolvers, so that if the first one cannot process the directive (eg: through `resolveCanProcess`, the next one can do it)
                foreach ($attachedDirectiveResolvers as $directiveResolver) {
                    $directiveName = $directiveResolver->getDirectiveName();
                    $directiveNameResolvers[$directiveName][] = $directiveResolver;
                }
                // Continue iterating for the class parents
            } while ($class = get_parent_class($class));
        }

        // Validate that the user has access to the directives (eg: can remove access to them for non logged-in users)
        $directiveNameResolvers = $this->filterDirectiveNameResolvers($directiveNameResolvers);

        return $directiveNameResolvers;
    }

    protected function calculateFieldNamesToResolve(): array
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();

        $fieldNames = [];

        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        $class = $this->getTypeResolverClassToCalculateSchema();
        do {
            /** @var FieldResolverInterface[] */
            $attachedFieldResolvers = $attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::FIELDRESOLVERS);
            foreach ($attachedFieldResolvers as $fieldResolver) {
                $extensionFieldNames = $this->getFieldNamesResolvedByFieldResolver($fieldResolver);
                $fieldNames = array_merge(
                    $fieldNames,
                    $extensionFieldNames
                );
            }
            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        return array_values(array_unique($fieldNames));
    }
}
