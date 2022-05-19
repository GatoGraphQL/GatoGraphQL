<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

use PoP\ComponentModel\App;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentField;
use PoP\ComponentModel\GraphQLEngine\Model\FieldFragmentModelsTuple;
use PoP\ComponentModel\ComponentProcessors\AbstractQueryDataComponentProcessor;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

abstract class AbstractRelationalFieldQueryDataComponentProcessor extends AbstractQueryDataComponentProcessor
{
    protected const COMPONENT_ATTS_FIELD_IDS = 'fieldIDs';
    protected const COMPONENT_ATTS_IGNORE_CONDITIONAL_FIELDS = 'ignoreConditionalFields';

    /**
     * @return FieldFragmentModelsTuple[]
     */
    protected function getFieldFragmentModelsTuples(?array $componentAtts): array
    {
        if ($componentAtts === null) {
            /**
             * There are not virtual component atts when loading the component
             * the first time (i.e. for the fields at the root level).
             */
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
        $fragments = $executableDocument->getDocument()->getFragments();
        $fieldFragmentModelsTuples = [];
        foreach ($executableDocument->getRequestedOperations() as $operation) {
            $fieldFragmentModelsTuples = array_merge(
                $fieldFragmentModelsTuples,
                $this->getAllFieldFragmentModelsTuplesFromFieldsOrFragmentBonds(
                    $operation->getFieldsOrFragmentBonds(),
                    $fragments,
                    $recursive
                )
            );
        }
        return $fieldFragmentModelsTuples;
    }

    /**
     * ID to uniquely identify the AST element
     */
    protected function getFieldUniqueID(FieldInterface $field, bool $aliasFriendly = false): string
    {
        return sprintf(
            $aliasFriendly ? '%s%sx%s' : '%s([%s,%s])',
            $field->getAlias() ?? $field->getName(),
            $field->getLocation()->getLine(),
            $field->getLocation()->getColumn()
        );
    }

    /**
     * @return LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        if (App::getState('does-api-query-have-errors')) {
            return [];
        }

        $componentAtts = $component[2] ?? null;
        $leafFieldFragmentModelsTuples = $this->getLeafFieldFragmentModelsTuples($componentAtts);

        if ($this->ignoreConditionalFields($componentAtts)) {
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
            fn (LeafField $leafField) => LeafComponentField::fromLeafField($leafField),
            $leafFields
        );
    }

    /**
     * Flag used to process the conditional field from the component or not
     */
    public function ignoreConditionalFields(?array $componentAtts): bool
    {
        return $componentAtts === null ? true : $componentAtts[self::COMPONENT_ATTS_IGNORE_CONDITIONAL_FIELDS] ?? true;
    }

    /**
     * @return FieldFragmentModelsTuple[]
     */
    protected function getLeafFieldFragmentModelsTuples(?array $componentAtts): array
    {
        $fieldFragmentModelsTuples = $this->getFieldFragmentModelsTuples($componentAtts);
        return array_filter(
            $fieldFragmentModelsTuples,
            fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getField() instanceof LeafField
        );
    }

    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalSubcomponents(array $component): array
    {
        if (App::getState('does-api-query-have-errors')) {
            return [];
        }

        $componentAtts = $component[2] ?? null;
        $relationalFieldFragmentModelsTuples = $this->getRelationalFieldFragmentModelsTuples($componentAtts);

        if ($this->ignoreConditionalFields($componentAtts)) {
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
                [$this, 'getFieldUniqueID'],
                $nestedFields
            );
            $nestedModule = [
                $component[0],
                $component[1],
                [
                    self::COMPONENT_ATTS_FIELD_IDS => $nestedFieldIDs,
                ]
            ];
            $ret[] = RelationalComponentField::fromRelationalField(
                $relationalField,
                [
                    $nestedModule,
                ]
            );
        }
        return $ret;
    }

    /**
     * @return FieldFragmentModelsTuple[]
     */
    protected function getRelationalFieldFragmentModelsTuples(?array $componentAtts): array
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
     * Using `getConditionalOnDataFieldRelationalSubcomponents` to
     * load relational fields does not work, because the component to
     * process entry "author" is added twice
     * (once "ignoreConditionalFields" => true, once => false) and both
     * of them will add their entry "author" under 'conditional-data-fields',
     * so it tries to retrieve field "author" from type "User", which is an error.
     *
     * As a solution, also treat "author" as a leaf, which works well:
     * author(ignoreConditionalFields=>true) is the simple shortcut to
     * author(ignoreConditionalFields=>false), so there's no domain
     * switching required.
     *
     * @return ConditionalLeafComponentField[]
     *
     * @todo Remove all commented code below this function
     */
    public function getConditionalOnDataFieldSubcomponents(array $component): array
    {
        if (App::getState('does-api-query-have-errors')) {
            return [];
        }

        $componentAtts = $component[2] ?? null;
        if (!$this->ignoreConditionalFields($componentAtts)) {
            return [];
        }

        $fieldFragmentModelsTuples = $this->getFieldFragmentModelsTuples($componentAtts);

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
        $conditionalLeafComponentFields = [];
        foreach ($fragmentModelListNameFields as $fragmentModelListName => $fragmentModelListFields) {
            $fragmentModels = $fragmentModelListNameItems[$fragmentModelListName];
            $fragmentModelListFieldIDs = array_map(
                fn (FieldInterface $field) => $this->getFieldUniqueID($field),
                $fragmentModelListFields,
            );
            $fragmentModelListNestedModule = [
                $component[0],
                $component[1],
                [
                    self::COMPONENT_ATTS_FIELD_IDS => $fragmentModelListFieldIDs,
                    self::COMPONENT_ATTS_IGNORE_CONDITIONAL_FIELDS => false,
                ]
            ];
            $fragmentModelListFieldAliasFriendlyIDs = array_map(
                fn (FieldInterface $field) => $this->getFieldUniqueID($field, true),
                $fragmentModelListFields,
            );
            /**
             * Create a new field that will evaluate if the fragment
             * must be applied or not. If applied, only then
             * the field within the fragment will be resolved
             */
            $conditionalLeafComponentFields[] = new ConditionalLeafComponentField(
                'isTypeOrImplementsAll',
                [
                    $fragmentModelListNestedModule,
                ],
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
                 */
                sprintf(
                    '_%s_%s_%s_',
                    implode('_', $fragmentModelListFieldAliasFriendlyIDs),
                    'isTypeOrImplementsAll',
                    $fragmentModelListName
                ),
                [
                    new Argument(
                        'typesOrInterfaces',
                        new InputList(
                            $fragmentModels,
                            LocationHelper::getNonSpecificLocation()
                        ),
                        LocationHelper::getNonSpecificLocation()
                    ),
                ]
            );
        }
        return $conditionalLeafComponentFields;
    }
    // /**
    //  * @return ConditionalLeafComponentField[]
    //  */
    // public function getConditionalOnDataFieldSubcomponents(array $component): array
    // {
    //     $componentAtts = $component[2] ?? null;
    //     if (!$this->ignoreConditionalFields($componentAtts)) {
    //         return [];
    //     }

    //     $leafFieldFragmentModelsTuples = $this->getLeafFieldFragmentModelsTuples($componentAtts);

    //     /**
    //      * Only retrieve fields contained within fragments
    //      */
    //     $leafFieldFragmentModelsTuples = array_filter(
    //         $leafFieldFragmentModelsTuples,
    //         fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getFragmentModels() !== []
    //     );

    //     $conditionalLeafComponentFields = [];
    //     foreach ($leafFieldFragmentModelsTuples as $fieldFragmentModelsTuple) {
    //         $field = $fieldFragmentModelsTuple->getField();
    //         $location = $field->getLocation();
    //         $nestedModule = [
    //             $component[0],
    //             $component[1],
    //             [
    //                 self::COMPONENT_ATTS_FIELD_IDS => [$this->getFieldUniqueID($field)],
    //                 self::COMPONENT_ATTS_IGNORE_CONDITIONAL_FIELDS => false,
    //             ]
    //         ];
    //         /**
    //          * Create a new field that will evaluate if the fragment
    //          * must be applied or not. If applied, only then
    //          * the field within the fragment will be resolved
    //          */
    //         $conditionalLeafComponentFields[] = new ConditionalLeafComponentField(
    //             'isTypeOrImplementsAll',
    //             [
    //                 $nestedModule
    //             ],
    //             // Create a unique alias to avoid conflicts
    //             sprintf(
    //                 '_isTypeOrImplementsAll_%s_%s_',
    //                 $location->getLine(),
    //                 $location->getColumn()
    //             ),
    //             [
    //                 new Argument(
    //                     'typesOrInterfaces',
    //                     new InputList(
    //                         $fieldFragmentModelsTuple->getFragmentModels(),
    //                         $location
    //                     ),
    //                     $location
    //                 ),
    //             ]
    //         );
    //     }
    //     return $conditionalLeafComponentFields;
    // }
    // /**
    //  * @return ConditionalRelationalComponentField[]
    //  */
    // public function getConditionalOnDataFieldRelationalSubcomponents(array $component): array
    // {
    //     $componentAtts = $component[2] ?? null;
    //     if (!$this->ignoreConditionalFields($componentAtts)) {
    //         return [];
    //     }

    //     $relationalFieldFragmentModelsTuples = $this->getRelationalFieldFragmentModelsTuples($componentAtts);

    //     /**
    //      * Only retrieve fields contained within fragments
    //      */
    //     $relationalFieldFragmentModelsTuples = array_filter(
    //         $relationalFieldFragmentModelsTuples,
    //         fn (FieldFragmentModelsTuple $fieldFragmentModelsTuple) => $fieldFragmentModelsTuple->getFragmentModels() !== []
    //     );

    //     $conditionalRelationalComponentFields = [];
    //     foreach ($relationalFieldFragmentModelsTuples as $fieldFragmentModelsTuple) {
    //         /** @var RelationalField */
    //         $relationalField = $fieldFragmentModelsTuple->getField();
    //         $location = $relationalField->getLocation();
    //         $nestedModule = [
    //             $component[0],
    //             $component[1],
    //             [
    //                 self::COMPONENT_ATTS_FIELD_IDS => [$this->getFieldUniqueID($relationalField)],
    //                 self::COMPONENT_ATTS_IGNORE_CONDITIONAL_FIELDS => false,
    //             ]
    //         ];
    //         $nestedRelationalComponentField = RelationalComponentField::fromRelationalField(
    //             $relationalField,
    //             [
    //                 $nestedModule
    //             ],
    //         );
    //         /**
    //          * Create a new field that will evaluate if the fragment
    //          * must be applied or not. If applied, only then
    //          * the field within the fragment will be resolved
    //          */
    //         $conditionalRelationalComponentFields[] = new ConditionalRelationalComponentField(
    //             'isTypeOrImplementsAll',
    //             [
    //                 $nestedRelationalComponentField
    //             ],
    //             // Create a unique alias to avoid conflicts
    //             sprintf(
    //                 '_isTypeOrImplementsAll_%s_%s_',
    //                 $location->getLine(),
    //                 $location->getColumn()
    //             ),
    //             [
    //                 new Argument(
    //                     'typesOrInterfaces',
    //                     new InputList(
    //                         $fieldFragmentModelsTuple->getFragmentModels(),
    //                         $location
    //                     ),
    //                     $location
    //                 ),
    //             ]
    //         );
    //     }
    //     return $conditionalRelationalComponentFields;
    // }

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
