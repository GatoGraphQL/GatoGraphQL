<?php

declare(strict_types=1);

namespace PoPAPI\APIMirrorQuery\DataStructureFormatters;

use PoP\ComponentModel\Constants\Constants;
use PoP\ComponentModel\Constants\FieldOutputKeys;
use PoP\ComponentModel\DataStructureFormatters\AbstractJSONDataStructureFormatter;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\App;
use SplObjectStorage;

class MirrorQueryDataStructureFormatter extends AbstractJSONDataStructureFormatter
{
    public function getName(): string
    {
        return 'mirrorquery';
    }

    /**
     * Provide the Fields to be printed on the output.
     *
     * @return FieldInterface[]
     */
    protected function getFields(): array
    {
        $executableDocument = App::getState('executable-document-ast');

        // Make sure the GraphQL query exists and was parsed properly into an AST
        if ($executableDocument === null) {
            return [];
        }
        /** @var ExecutableDocument $executableDocument */

        /**
         * Return the root level Fields
         */
        return $this->getFieldsFromExecutableDocument($executableDocument);
    }

    /**
     * @return FieldInterface[]
     */
    protected function getFieldsFromExecutableDocument(
        ExecutableDocument $executableDocument,
    ): array {
        $fragments = $executableDocument->getDocument()->getFragments();
        $fields = [];
        foreach ($executableDocument->getRequestedOperations() as $operation) {
            $fields = array_merge(
                $fields,
                $this->getAllFieldsFromFieldsOrFragmentBonds(
                    $operation->getFieldsOrFragmentBonds(),
                    $fragments,
                )
            );
        }
        return $fields;
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FieldInterface[]
     */
    protected function getAllFieldsFromFieldsOrFragmentBonds(
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): array {
        /** @var FieldInterface[] */
        $fields = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->getFragment($fragmentReference->getName(), $fragments);
                if ($fragment === null) {
                    continue;
                }
                $allFieldsFromFieldsOrFragmentBonds = $this->getAllFieldsFromFieldsOrFragmentBonds(
                    $fragment->getFieldsOrFragmentBonds(),
                    $fragments,
                );
                $fields = array_merge(
                    $fields,
                    $allFieldsFromFieldsOrFragmentBonds
                );
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $allFieldsFromFieldsOrFragmentBonds = $this->getAllFieldsFromFieldsOrFragmentBonds(
                    $inlineFragment->getFieldsOrFragmentBonds(),
                    $fragments,
                );
                $fields = array_merge(
                    $fields,
                    $allFieldsFromFieldsOrFragmentBonds
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $fields[] = $field;
        }
        return $fields;
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

    public function getFormattedData(array $data): array
    {
        $fields = $this->getFields();
        if (!$fields) {
            return [];
        }

        /**
         * Re-create the shape of the query by iterating through all objectIDs
         * and all required fields, getting the data from the corresponding
         * typeOutputKeyPath
         */
        $ret = [];
        $databases = $data['databases'] ?? [];
        $unionTypeOutputKeyIDs = $data['unionTypeOutputKeyIDs'] ?? [];
        $datasetComponentData = $data['datasetcomponentdata'] ?? [];
        foreach ($datasetComponentData as $componentName => $componentData) {
            $typeOutputKeyPaths = $data['datasetcomponentsettings'][$componentName]['outputKeys'] ?? [];
            $objectIDorIDs = $componentData['objectIDs'];
            $this->addData($ret, $fields, $databases, $unionTypeOutputKeyIDs, $objectIDorIDs, FieldOutputKeys::ID, $typeOutputKeyPaths, false);
        }
        return $ret;
    }

    /**
     * @param array<string,mixed>|null $ret
     * @param FieldInterface[] $fields
     * @param array<string,array<string|int,array<string,mixed>>> $databases
     * @param array<string,array<string|int,array<string,array<string|int>|string|int|null>>> $unionTypeOutputKeyIDs
     * @param array<string|int>|string|integer $objectIDorIDs
     * @param array<string> $typeOutputKeyPaths
     */
    protected function addData(?array &$ret, array $fields, array &$databases, array &$unionTypeOutputKeyIDs, array|string|int $objectIDorIDs, string $objectKeyPath, array &$typeOutputKeyPaths, bool $concatenateField = true): void
    {
        // Property fields have numeric key only. From them, obtain the fields to print for the object
        $leafFields = array_filter(
            $fields,
            fn (FieldInterface $field) => $field instanceof LeafField
        );

        // All other fields must be nested, to keep fetching data for the object relationships
        $relationalFields = array_filter(
            $fields,
            fn (FieldInterface $field) => $field instanceof RelationalField
        );

        // The results can be a single ID or value, or an array of IDs
        if (is_array($objectIDorIDs)) {
            foreach ($objectIDorIDs as $objectID) {
                // Add a new array for this DB object, where to return all its properties
                $ret[] = [];
                $resolvedObjectRet = &$ret[count($ret) - 1];
                $this->addObjectData($resolvedObjectRet, $leafFields, $relationalFields, $databases, $unionTypeOutputKeyIDs, $objectID, $objectKeyPath, $typeOutputKeyPaths, $concatenateField);
            }
            return;
        }
        $objectID = $objectIDorIDs;
        $this->addObjectData($ret, $leafFields, $relationalFields, $databases, $unionTypeOutputKeyIDs, $objectID, $objectKeyPath, $typeOutputKeyPaths, $concatenateField);
    }

    /**
     * @param array<string,mixed>|null $resolvedObjectRet
     * @param LeafField[] $leafFields
     * @param RelationalField[] $relationalFields
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $databases
     * @param array<string,array<string|int,array<string,array<string|int>|string|int|null>>> $unionTypeOutputKeyIDs
     * @param array<string> $typeOutputKeyPaths
     */
    protected function addObjectData(?array &$resolvedObjectRet, array $leafFields, array $relationalFields, array &$databases, array &$unionTypeOutputKeyIDs, string|int $objectID, string $objectKeyPath, array &$typeOutputKeyPaths, bool $concatenateField): void
    {
        // If there are no property fields and no relationalFields, then do nothing.
        // Otherwise, it could throw an error on `extractObjectTypeAndID`
        // because it only has the ID and not the name of the type
        // Eg: When a validation on the last field fails, such as: /?query=posts.id(
        if (!$leafFields && !$relationalFields) {
            return;
        }

        // Execute for all fields other than the first one, "root", for both UnionTypeResolvers and non-union ones
        // This is because if it's a relational field that comes after a UnionTypeResolver, its typeOutputKey could not be inferred (since it depends from the resolvedObject, and can't be obtained in the settings, where "outputKeys" is obtained and which doesn't depend on data items)
        // Eg: /?query=content.comments.id. In this case, "content" is handled by UnionTypeResolver, and "comments" would not be found since its entry can't be added under "datasetcomponentsettings.outputKeys", since the component (of class AbstractRelationalFieldQueryDataComponentProcessor) with a UnionTypeResolver can't resolve the 'succeeding-typeResolver' to set to its subcomponents
        if ($concatenateField) {
            list(
                $typeOutputKey,
                $objectID
            ) = UnionTypeHelpers::extractObjectTypeAndID(
                // If the object could not be loaded, $objectID will be all ID, with no $typeOutputKey
                // Since that could be an int, the strict typing would throw an error,
                // so make sure to type it as a string
                (string) $objectID
            );
        } else {
            // Add all properties requested from the object
            $typeOutputKey = $typeOutputKeyPaths[$objectKeyPath];
        }
        // If there is no typeOutputKey, it is an error (eg: requesting posts.cats.saranga)
        if (!$typeOutputKey) {
            return;
        }

        /** @var SplObjectStorage<FieldInterface,mixed> */
        $resolvedObject = $databases[$typeOutputKey][$objectID] ?? new SplObjectStorage();
        foreach ($leafFields as $leafField) {
            /**
             * If the key doesn't exist, then do nothing.
             */
            if (!$resolvedObject->contains($leafField)) {
                continue;
            }
            $resolvedObjectRet[$leafField->getOutputKey()] = $resolvedObject[$leafField];
        }

        // Add the nested levels
        foreach ($relationalFields as $relationalField) {
            /**
             * If the key doesn't exist, then do nothing.
             */
            if (!$resolvedObject->contains($relationalField)) {
                continue;
            }

            $relationalFieldOutputKey = $relationalField->getOutputKey();

            // If it's null, directly assign the null to the result
            if ($resolvedObject[$relationalField] === null) {
                $resolvedObjectRet[$relationalFieldOutputKey] = null;
                continue;
            }

            /**
             * The first field, "id", needs not be concatenated. All the others do need.
             *
             * This is legacy code: this variable is calculated but will not be used,
             * since the Type from which to retrieve the data is already codified
             * under the objectID for all fields other than the "root", and this one
             * has its field hardcoded as "id".
             */
            $nextField = ($concatenateField ? $objectKeyPath . Constants::RELATIONAL_FIELD_PATH_SEPARATOR : '') . $relationalFieldOutputKey;

            // The type with ID may be stored under $unionTypeOutputKeyIDs
            $unionTypeOutputKeyID = $unionTypeOutputKeyIDs[$typeOutputKey][$objectID][$relationalField] ?? null;

            // Add a new subarray for the nested property
            $resolvedObjectNestedPropertyRet = &$resolvedObjectRet[$relationalFieldOutputKey];

            // If it is an empty array, then directly add an empty array as the result
            if (is_array($resolvedObject[$relationalField]) && empty($resolvedObject[$relationalField])) {
                $resolvedObjectRet[$relationalFieldOutputKey] = [];
                continue;
            }

            if (!empty($resolvedObjectNestedPropertyRet)) {
                // 1. If we load a relational property as its ID, and then load properties on the corresponding object, then it will fail because it will attempt to add a property to a non-array element
                // Eg: /posts/api/graphql/?query=id|author,author.name will first return "author => 1" and on the "1" element add property "name"
                // Then, if this situation happens, simply override the ID (which is a scalar value, such as an int or string) with an object with the 'id' property
                if (!is_array($resolvedObjectNestedPropertyRet)) {
                    $resolvedObjectRet[$relationalFieldOutputKey] = [
                        FieldOutputKeys::ID => $resolvedObjectRet[$relationalFieldOutputKey],
                    ];
                } else {
                    // 2. If the previous iteration loaded an array of IDs, then override this value with an empty array
                    $resolvedObjectRet[$relationalFieldOutputKey] = [];
                }
            }
            /**
             * The RelationalField can contain fragments.
             * Replace these into fields.
             */
            /** @var ExecutableDocument */
            $executableDocument = App::getState('executable-document-ast');
            $fragments = $executableDocument->getDocument()->getFragments();
            $relationalNestedFields = $this->getAllFieldsFromFieldsOrFragmentBonds(
                $relationalField->getFieldsOrFragmentBonds(),
                $fragments
            );
            $this->addData($resolvedObjectNestedPropertyRet, $relationalNestedFields, $databases, $unionTypeOutputKeyIDs, $unionTypeOutputKeyID ?? $resolvedObject[$relationalField], $nextField, $typeOutputKeyPaths);
        }
    }
}
