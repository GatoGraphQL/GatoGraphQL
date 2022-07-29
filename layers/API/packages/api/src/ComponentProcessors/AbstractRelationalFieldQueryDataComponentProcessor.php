<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractQueryDataComponentProcessor;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\FieldFragmentModelsTuple;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoPAPI\API\QueryResolution\QueryASTTransformationServiceInterface;

abstract class AbstractRelationalFieldQueryDataComponentProcessor extends AbstractQueryDataComponentProcessor
{
    protected const COMPONENT_ATTS_FIELD_IDS = 'fieldIDs';
    protected const COMPONENT_ATTS_IGNORE_CONDITIONAL_FIELDS = 'ignoreConditionalFields';

    /**
     * Because fields are stored in SplObjectStorage,
     * the same instance must be retrieved in every case.
     * Then, cache and reuse every created field
     *
     * @var array<string,LeafField>
     */
    private array $fieldInstanceContainer = [];

    private ?QueryASTTransformationServiceInterface $queryASTTransformationService = null;

    final public function setQueryASTTransformationService(QueryASTTransformationServiceInterface $queryASTTransformationService): void
    {
        $this->queryASTTransformationService = $queryASTTransformationService;
    }
    final protected function getQueryASTTransformationService(): QueryASTTransformationServiceInterface
    {
        return $this->queryASTTransformationService ??= $this->instanceManager->getInstance(QueryASTTransformationServiceInterface::class);
    }

    /**
     * @return FieldFragmentModelsTuple[]
     */
    protected function getFieldFragmentModelsTuples(array $componentAtts): array
    {
        /**
         * There are not virtual component atts when loading the component
         * the first time (i.e. for the fields at the root level).
         */
        if ($componentAtts === []) {
            $executableDocument = App::getState('executable-document-ast');

            // Make sure the GraphQL query exists and was parsed properly into an AST
            if ($executableDocument === null) {
                return [];
            }
            /** @var ExecutableDocument $executableDocument */

            /**
             * Because componentAtts are serialized/unserialized,
             * cannot pass the Field object directly in them.
             *
             * Instead, first generate a dictionary with all the Fields
             * in the GraphQL query, and place them under a unique ID.
             * Then this "fieldID" will be passed in the componentAtts
             */
            $this->maybeStoreAstFieldFragmentModelsTuplesInAppState($executableDocument);

            /**
             * Return the root level Fields
             */
            return $this->getFieldFragmentModelsTuplesFromExecutableDocument($executableDocument, false);
        }

        /**
         * When the virtual component has atts, the field IDs are coded within.
         */
        return $this->retrieveAstFieldFragmentModelsTuplesFromAppState($componentAtts[self::COMPONENT_ATTS_FIELD_IDS]);
    }

    /**
     * Retrieve the Fields stored in the AppState from the passed "fieldIDs".
     *
     * @param string[] $fieldIDs
     * @return FieldFragmentModelsTuple[]
     */
    protected function retrieveAstFieldFragmentModelsTuplesFromAppState(array $fieldIDs): array
    {
        $appStateFieldFragmentModelsTuples = App::getState('executable-document-ast-field-fragmentmodels-tuples');
        $query = App::getState('query');
        $fieldFragmentModelsTuples = [];
        foreach ($fieldIDs as $fieldID) {
            $fieldFragmentModelsTuples[] = $appStateFieldFragmentModelsTuples[$query][$fieldID];
        }
        return $fieldFragmentModelsTuples;
    }

    /**
     * Generate a dictionary with all the Fields
     * in the GraphQL query, placed under their unique ID,
     * and set it in the AppState
     */
    protected function maybeStoreAstFieldFragmentModelsTuplesInAppState(
        ExecutableDocument $executableDocument,
    ): void {
        // Only do it the first time the query is parsed
        $appStateManager = App::getAppStateManager();
        $appStateFieldFragmentModelsTuples = [];
        if ($appStateManager->has('executable-document-ast-field-fragmentmodels-tuples')) {
            $appStateFieldFragmentModelsTuples = $appStateManager->get('executable-document-ast-field-fragmentmodels-tuples');
        }
        $query = App::getState('query');
        if (isset($appStateFieldFragmentModelsTuples[$query])) {
            return;
        }

        $fieldFragmentModelsTuples = $this->getFieldFragmentModelsTuplesFromExecutableDocument($executableDocument, true);
        $appStateFieldFragmentModelsTuples[$query] = [];
        foreach ($fieldFragmentModelsTuples as $fieldFragmentModelsTuple) {
            $appStateFieldFragmentModelsTuples[$query][$this->getFieldUniqueID($fieldFragmentModelsTuple->getField())] = $fieldFragmentModelsTuple;
        }
        $appStateManager->override('executable-document-ast-field-fragmentmodels-tuples', $appStateFieldFragmentModelsTuples);
    }

    /**
     * @return FieldFragmentModelsTuple[]
     */
    protected function getFieldFragmentModelsTuplesFromExecutableDocument(
        ExecutableDocument $executableDocument,
        bool $recursive,
    ): array {
        $fieldFragmentModelsTuples = [];
        $fragments = $executableDocument->getDocument()->getFragments();
        $requestedOperations = $executableDocument->getRequestedOperations();

        /**
         * Multiple Query Execution: In order to have the fields
         * of the subsequent operations be resolved in the same
         * order as the operations (which is necessary for `@export`
         * to work), then wrap them on a "self" field.
         */
        $operationFieldOrFragmentBonds = $this->getQueryASTTransformationService()->prepareOperationFieldAndFragmentBondsForMultipleQueryExecution(
            $requestedOperations,
            $fragments,
        );
        /** @var OperationInterface $operation */
        foreach ($operationFieldOrFragmentBonds as $operation) {
            $fieldOrFragmentBonds = $operationFieldOrFragmentBonds[$operation];
            $fieldFragmentModelsTuples = array_merge(
                $fieldFragmentModelsTuples,
                $this->getAllFieldFragmentModelsTuplesFromFieldsOrFragmentBonds(
                    $fieldOrFragmentBonds,
                    $fragments,
                    $recursive
                )
            );
        }
        return $fieldFragmentModelsTuples;
    }

    /**
     * ID to uniquely identify the AST element.
     *
     * As dynamically-generated AST elements share the
     * same location (and so 2 objects could produce the same ID),
     * also append the unique object hash for them.
     */
    protected function getFieldUniqueID(FieldInterface $field, bool $aliasFriendly = false): string
    {
        $location = $field->getLocation();
        $fieldUniqueID = sprintf(
            $aliasFriendly ? '%s%sx%s' : '%s([%s,%s])',
            $field->getAlias() ?? $field->getName(),
            $location->getLine(),
            $location->getColumn()
        );
        if ($location === LocationHelper::getNonSpecificLocation()) {
            return sprintf(
                '%s #%s',
                $fieldUniqueID,
                spl_object_hash($field)
            );
        }
        return $fieldUniqueID;
    }

    /**
     * @return LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(Component $component, array &$props): array
    {
        if (App::getState('does-api-query-have-errors')) {
            return [];
        }

        $leafFieldFragmentModelsTuples = $this->getLeafFieldFragmentModelsTuples($component->atts);

        if ($this->ignoreConditionalFields($component->atts)) {
            /**
             * Only retrieve fields not contained within fragments
             * (those will be handled via a conditional on the fragment model)
             */
            $leafFieldFragmentModelsTuples = array_filter(
                $leafFieldFragmentModelsTuples,
                fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getFragmentModels() === []
            );
        }

        /** @var LeafField[] */
        $leafFields = array_map(
            fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getField(),
            $leafFieldFragmentModelsTuples
        );

        return array_map(
            LeafComponentFieldNode::fromLeafField(...),
            $leafFields
        );
    }

    /**
     * Flag used to process the conditional field from the component or not
     */
    public function ignoreConditionalFields(array $componentAtts): bool
    {
        return $componentAtts === [] ? true : $componentAtts[self::COMPONENT_ATTS_IGNORE_CONDITIONAL_FIELDS] ?? true;
    }

    /**
     * @return FieldFragmentModelsTuple[]
     */
    protected function getLeafFieldFragmentModelsTuples(array $componentAtts): array
    {
        $fieldFragmentModelsTuples = $this->getFieldFragmentModelsTuples($componentAtts);
        return array_filter(
            $fieldFragmentModelsTuples,
            fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getField() instanceof LeafField
        );
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(Component $component): array
    {
        if (App::getState('does-api-query-have-errors')) {
            return [];
        }

        $relationalFieldFragmentModelsTuples = $this->getRelationalFieldFragmentModelsTuples($component->atts);

        if ($this->ignoreConditionalFields($component->atts)) {
            /**
             * Only retrieve fields not contained within fragments
             * (those will be handled via a conditional on the fragment model)
             */
            $relationalFieldFragmentModelsTuples = array_filter(
                $relationalFieldFragmentModelsTuples,
                fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getFragmentModels() === []
            );
        }

        /** @var RelationalField[] */
        $relationalFields = array_map(
            fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getField(),
            $relationalFieldFragmentModelsTuples
        );

        $executableDocument = App::getState('executable-document-ast');
        if ($executableDocument === null) {
            return [];
        }

        /** @var ExecutableDocument $executableDocument */
        $fragments = $executableDocument->getDocument()->getFragments();
        $ret = [];

        /**
         * Create a "virtual" component with the fields
         * corresponding to the next level component.
         */
        foreach ($relationalFields as $relationalField) {
            $allFieldFragmentModelsFromFieldsOrFragmentBonds = $this->getAllFieldFragmentModelsTuplesFromFieldsOrFragmentBonds(
                $relationalField->getFieldsOrFragmentBonds(),
                $fragments,
                false
            );
            $nestedFields = array_map(
                fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getField(),
                $allFieldFragmentModelsFromFieldsOrFragmentBonds
            );
            $nestedFieldIDs = array_map(
                $this->getFieldUniqueID(...),
                $nestedFields
            );
            $nestedComponent = new Component(
                $component->processorClass,
                $component->name,
                [
                    self::COMPONENT_ATTS_FIELD_IDS => $nestedFieldIDs,
                ]
            );
            $ret[] = RelationalComponentFieldNode::fromRelationalField(
                $relationalField,
                [
                    $nestedComponent,
                ]
            );
        }
        return $ret;
    }

    /**
     * @return FieldFragmentModelsTuple[]
     */
    protected function getRelationalFieldFragmentModelsTuples(array $componentAtts): array
    {
        $fieldFragmentModelsTuples = $this->getFieldFragmentModelsTuples($componentAtts);
        return array_filter(
            $fieldFragmentModelsTuples,
            fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getField() instanceof RelationalField
        );
    }

    /**
     * Watch out! This function loads both leaf fields (eg: "date") and
     * relational fields (eg: "author").
     *
     * Using `getConditionalRelationalComponentFieldNodes` to
     * load relational fields does not work, because the component to
     * process entry "author" is added twice
     * (once "ignoreConditionalFields" => true, once => false) and both
     * of them will add their entry "author" under 'conditional-component-field-nodes',
     * so it tries to retrieve field "author" from type "User", which is an error.
     *
     * As a solution, also treat "author" as a leaf, which works well:
     * author(ignoreConditionalFields=>true) is the simple shortcut to
     * author(ignoreConditionalFields=>false), so there's no domain
     * switching required.
     *
     * @return ConditionalLeafComponentFieldNode[]
     */
    public function getConditionalLeafComponentFieldNodes(Component $component): array
    {
        if (App::getState('does-api-query-have-errors')) {
            return [];
        }

        if (!$this->ignoreConditionalFields($component->atts)) {
            return [];
        }

        $fieldFragmentModelsTuples = $this->getFieldFragmentModelsTuples($component->atts);

        /**
         * Only retrieve fields contained within fragments
         */
        $fieldFragmentModelsTuples = array_filter(
            $fieldFragmentModelsTuples,
            fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getFragmentModels() !== []
        );

        /**
         * First collect all fields for each combination of fragment models
         */
        /** @var array<string, string[]> */
        $fragmentModelListNameItems = [];
        /** @var array<string, FieldInterface[]> */
        $fragmentModelListNameFields = [];
        foreach ($fieldFragmentModelsTuples as $fieldFragmentModelsTuple) {
            $field = $fieldFragmentModelsTuple->getField();
            $fragmentModelListName = implode('_', $fieldFragmentModelsTuple->getFragmentModels());
            $fragmentModelListNameItems[$fragmentModelListName] = $fieldFragmentModelsTuple->getFragmentModels();
            $fragmentModelListNameFields[$fragmentModelListName][] = $field;
        }

        /**
         * Then iterate the list of all fragment model sets and, for each,
         * create a Conditional object with all the nested modules,
         * and with a single conditional field (to be used for retrieving
         * the data for all nested modules)
         */
        $conditionalLeafComponentFieldNodes = [];
        foreach ($fragmentModelListNameFields as $fragmentModelListName => $fragmentModelListFields) {
            $fragmentModels = $fragmentModelListNameItems[$fragmentModelListName];
            $fragmentModelListFieldIDs = array_map(
                $this->getFieldUniqueID(...),
                $fragmentModelListFields,
            );
            $fragmentModelListNestedComponent = new Component(
                $component->processorClass,
                $component->name,
                [
                    self::COMPONENT_ATTS_FIELD_IDS => $fragmentModelListFieldIDs,
                    self::COMPONENT_ATTS_IGNORE_CONDITIONAL_FIELDS => false,
                ]
            );
            $fragmentModelListFieldAliasFriendlyIDs = array_map(
                fn (FieldInterface $field) => $this->getFieldUniqueID($field, true),
                $fragmentModelListFields,
            );
            /**
             * Create a unique alias to avoid conflicts.
             *
             * Embedded in the alias are the required fragment models
             * to satisfy, and all the fields that depend on it,
             * so that if two fields have the same dependency,
             * this field is resolved once, not twice.
             *
             * Eg: 2 fields on the same fragment will have the same
             * dependency, and will re-use it:
             *
             * ```graphql
             * fragment PostData on Post {
             *   title
             *   date
             * }
             * ```
             *
             * It's also important for the location of each field
             * to be part of the alias, to make sure that,
             * if the same group of fields are applied under different
             * types (evidenced by their location on the GraphQL query)
             * then these are treated differently.
             *
             * An example of the generated alias is:
             *
             *   "_kind13x5_name14x5_isTypeOrImplementsAll___Type_"
             */
            $alias = sprintf(
                '_%s_%s_%s_',
                implode('_', $fragmentModelListFieldAliasFriendlyIDs),
                'isTypeOrImplementsAll',
                $fragmentModelListName
            );
            /**
             * Important! The `FieldInterface` instance must always be the same!
             * Because it will be placed on the SplObjectStorage,
             * and it's different objects, even if with the same properties,
             * it doesn't retrieve it.
             */
            if (!isset($this->fieldInstanceContainer[$alias])) {
                $this->fieldInstanceContainer[$alias] = new LeafField(
                    'isTypeOrImplementsAll',
                    $alias,
                    [
                        new Argument(
                            'typesOrInterfaces',
                            new InputList(
                                $fragmentModels,
                                LocationHelper::getNonSpecificLocation()
                            ),
                            LocationHelper::getNonSpecificLocation()
                        ),
                    ],
                    [],
                    LocationHelper::getNonSpecificLocation()
                );
            }
            $leafField = $this->fieldInstanceContainer[$alias];

            /**
             * Create a new field that will evaluate if the fragment
             * must be applied or not. If applied, only then
             * the field within the fragment will be resolved
             */
            $conditionalLeafComponentFieldNodes[] = new ConditionalLeafComponentFieldNode(
                $leafField,
                [
                    $fragmentModelListNestedComponent,
                ],
            );
        }
        return $conditionalLeafComponentFieldNodes;
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FieldFragmentModelsTuple[] A list of the fields and what fragment "models" they need to satisfy to be resolved
     */
    protected function getAllFieldFragmentModelsTuplesFromFieldsOrFragmentBonds(
        array $fieldsOrFragmentBonds,
        array $fragments,
        bool $recursive
    ): array {
        /** @var FieldFragmentModelsTuple[] */
        $fieldFragmentModelsTuples = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->getFragment($fragmentReference->getName(), $fragments);
                if ($fragment === null) {
                    continue;
                }
                $allFieldFragmentModelsFromFieldsOrFragmentBonds = $this->getAllFieldFragmentModelsTuplesFromFieldsOrFragmentBonds(
                    $fragment->getFieldsOrFragmentBonds(),
                    $fragments,
                    false // Fragments: Stop the recursion to avoid adding duplicate items
                );
                foreach ($allFieldFragmentModelsFromFieldsOrFragmentBonds as $fieldFragmentModelsTuple) {
                    $fieldFragmentModelsTuple->addFragmentModel($fragment->getModel());
                }
                $fieldFragmentModelsTuples = array_merge(
                    $fieldFragmentModelsTuples,
                    $allFieldFragmentModelsFromFieldsOrFragmentBonds
                );
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $allFieldFragmentModelsFromFieldsOrFragmentBonds = $this->getAllFieldFragmentModelsTuplesFromFieldsOrFragmentBonds(
                    $inlineFragment->getFieldsOrFragmentBonds(),
                    $fragments,
                    false // Fragments: Stop the recursion to avoid adding duplicate items
                );
                foreach ($allFieldFragmentModelsFromFieldsOrFragmentBonds as $fieldFragmentModelsTuple) {
                    $fieldFragmentModelsTuple->addFragmentModel($inlineFragment->getTypeName());
                }
                $fieldFragmentModelsTuples = array_merge(
                    $fieldFragmentModelsTuples,
                    $allFieldFragmentModelsFromFieldsOrFragmentBonds
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $fieldFragmentModelsTuples[] = new FieldFragmentModelsTuple($field);
        }
        if (!$recursive) {
            return $fieldFragmentModelsTuples;
        }

        /**
         * Recursive: also obtain the fields nested within the fields
         */
        $recursiveFieldFragmentModelsTuples = [];
        foreach ($fieldFragmentModelsTuples as $fieldFragmentModelsTuple) {
            $recursiveFieldFragmentModelsTuples[] = $fieldFragmentModelsTuple;
            if ($fieldFragmentModelsTuple->getField() instanceof LeafField) {
                continue;
            }
            /** @var RelationalField */
            $relationalField = $fieldFragmentModelsTuple->getField();
            $recursiveFieldFragmentModelsTuples = array_merge(
                $recursiveFieldFragmentModelsTuples,
                $this->getAllFieldFragmentModelsTuplesFromFieldsOrFragmentBonds(
                    $relationalField->getFieldsOrFragmentBonds(),
                    $fragments,
                    $recursive
                )
            );
        }
        return $recursiveFieldFragmentModelsTuples;
    }

    /**
     * @param Fragment[] $fragments
     */
    protected function getFragment(
        string $fragmentName,
        array $fragments,
    ): ?Fragment {
        foreach ($fragments as $fragment) {
            if ($fragment->getName() === $fragmentName) {
                return $fragment;
            }
        }
        return null;
    }
}
