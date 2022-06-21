<?php

declare(strict_types=1);

namespace PoPAPI\APIMirrorQuery\DataStructureFormatters;

use PoP\ComponentModel\Constants\FieldOutputKeys;
use PoP\ComponentModel\DataStructureFormatters\AbstractJSONDataStructureFormatter;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use SplObjectStorage;

class MirrorQueryDataStructureFormatter extends AbstractJSONDataStructureFormatter
{
    public function getName(): string
    {
        return 'mirrorquery';
    }

    /**
     * @return array<string|int,string>
     */
    protected function getFields(): array
    {
        // Allow REST to override with default fields
        return App::getState('requested-query') ?? App::getState('executable-query') ?? [];
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
     * @param array<string|int,string|array<string>> $fields
     * @param array<string,array<string|int,array<string,mixed>>> $databases
     * @param array<string,array<string|int,array<string,array<string|int>|string|int|null>>> $unionTypeOutputKeyIDs
     * @param array<string|int>|string|integer $objectIDorIDs
     * @param array<string> $typeOutputKeyPaths
     */
    protected function addData(?array &$ret, array $fields, array &$databases, array &$unionTypeOutputKeyIDs, array|string|int $objectIDorIDs, string $objectKeyPath, array &$typeOutputKeyPaths, bool $concatenateField = true): void
    {
        // Property fields have numeric key only. From them, obtain the fields to print for the object
        $propertyFields = array_filter(
            $fields,
            fn (string|int $key) =>  is_numeric($key),
            ARRAY_FILTER_USE_KEY
        );

        // All other fields must be nested, to keep fetching data for the object relationships
        $nestedFields = array_filter(
            $fields,
            fn (string|int $key) => !is_numeric($key),
            ARRAY_FILTER_USE_KEY
        );

        // The results can be a single ID or value, or an array of IDs
        if (is_array($objectIDorIDs)) {
            foreach ($objectIDorIDs as $objectID) {
                // Add a new array for this DB object, where to return all its properties
                $ret[] = [];
                $resolvedObjectRet = &$ret[count($ret) - 1];
                $this->addObjectData($resolvedObjectRet, $propertyFields, $nestedFields, $databases, $unionTypeOutputKeyIDs, $objectID, $objectKeyPath, $typeOutputKeyPaths, $concatenateField);
            }
            return;
        }
        $objectID = $objectIDorIDs;
        $this->addObjectData($ret, $propertyFields, $nestedFields, $databases, $unionTypeOutputKeyIDs, $objectID, $objectKeyPath, $typeOutputKeyPaths, $concatenateField);
    }

    /**
     * Undocumented function
     *
     * @param array<string,mixed>|null $resolvedObjectRet
     * @param array<string> $propertyFields
     * @param array<string,array<string>> $nestedFields
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $databases
     * @param array<string,array<string|int,array<string,array<string|int>|string|int|null>>> $unionTypeOutputKeyIDs
     * @param string|integer $objectID
     * @param string $objectKeyPath
     * @param array<string> $typeOutputKeyPaths
     * @param boolean $concatenateField
     */
    protected function addObjectData(?array &$resolvedObjectRet, array $propertyFields, array $nestedFields, array &$databases, array &$unionTypeOutputKeyIDs, string|int $objectID, string $objectKeyPath, array &$typeOutputKeyPaths, bool $concatenateField): void
    {
        // If there are no property fields and no nestedFields, then do nothing.
        // Otherwise, it could throw an error on `extractObjectTypeAndID`
        // because it only has the ID and not the name of the type
        // Eg: When a validation on the last field fails, such as: /?query=posts.id(
        if (!$propertyFields && !$nestedFields) {
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
        foreach ($propertyFields as $propertyField) {
            // Only if the property has been set (in case of dbError it is not set)
            $propertyFieldOutputKey = $this->getFieldQueryInterpreter()->getFieldOutputKey($propertyField);

            // @todo Re-do this logic, by passing the FieldInterface directly
            /** @var FieldInterface|null */
            $propertyFieldInstance = null;
            /** @var FieldInterface[] */
            $resolvedObjectFields = iterator_to_array($resolvedObject);
            foreach ($resolvedObjectFields as $resolvedObjectField) {
                if ($resolvedObjectField->getOutputKey() === $propertyFieldOutputKey) {
                    $propertyFieldInstance = $resolvedObjectField;
                    break;
                }
            }

            if ($propertyFieldInstance !== null) {
                $resolvedObjectRet[$propertyFieldOutputKey] = $resolvedObject[$propertyFieldInstance];
            }
        }

        // Add the nested levels
        foreach ($nestedFields as $nestedField => $nestedPropertyFields) {
            $nestedFieldOutputKey = $this->getFieldQueryInterpreter()->getFieldOutputKey($nestedField);
        

            // @todo Re-do this logic, by passing the FieldInterface directly
            /** @var FieldInterface|null */
            $nestedFieldInstance = null;
            /** @var FieldInterface[] */
            $resolvedObjectFields = iterator_to_array($resolvedObject);
            foreach ($resolvedObjectFields as $resolvedObjectField) {
                if ($resolvedObjectField->getOutputKey() === $nestedFieldOutputKey) {
                    $nestedFieldInstance = $resolvedObjectField;
                    break;
                }
            }
            if ($nestedFieldInstance === null) {
                continue;
            }

            /**
             * If the key doesn't exist, then do nothing.
             *
             * This supports the "skip output if null" behaviour:
             * if it is to be skipped, there will be no value
             * (which is different than a null)
             */
            if (!$resolvedObject->contains($nestedFieldInstance)) {
                continue;
            }

            // If it's null, directly assign the null to the result
            if ($resolvedObject[$nestedFieldInstance] === null) {
                $resolvedObjectRet[$nestedFieldOutputKey] = null;
                continue;
            }

            // The first field, "id", needs not be concatenated. All the others do need
            $nextField = ($concatenateField ? $objectKeyPath . '.' : '') . $nestedFieldOutputKey;

            // The type with ID may be stored under $unionTypeOutputKeyIDs
            $unionTypeOutputKeyID = $unionTypeOutputKeyIDs[$typeOutputKey][$objectID][$nestedFieldInstance] ?? null;

            // Add a new subarray for the nested property
            $resolvedObjectNestedPropertyRet = &$resolvedObjectRet[$nestedFieldOutputKey];

            // If it is an empty array, then directly add an empty array as the result
            if (is_array($resolvedObject[$nestedFieldInstance]) && empty($resolvedObject[$nestedFieldInstance])) {
                $resolvedObjectRet[$nestedFieldOutputKey] = [];
                continue;
            }

            if (!empty($resolvedObjectNestedPropertyRet)) {
                // 1. If we load a relational property as its ID, and then load properties on the corresponding object, then it will fail because it will attempt to add a property to a non-array element
                // Eg: /posts/api/graphql/?query=id|author,author.name will first return "author => 1" and on the "1" element add property "name"
                // Then, if this situation happens, simply override the ID (which is a scalar value, such as an int or string) with an object with the 'id' property
                if (!is_array($resolvedObjectNestedPropertyRet)) {
                    $resolvedObjectRet[$nestedFieldOutputKey] = [
                        FieldOutputKeys::ID => $resolvedObjectRet[$nestedFieldOutputKey],
                    ];
                } else {
                    // 2. If the previous iteration loaded an array of IDs, then override this value with an empty array and initialize the ID again to this object, through adding property 'id' on the next iteration
                    // Eg: /api/graphql/?query=tags,tags.name
                    $resolvedObjectRet[$nestedFieldOutputKey] = [];
                    if (!in_array(FieldOutputKeys::ID, $nestedPropertyFields)) {
                        array_unshift($nestedPropertyFields, FieldOutputKeys::ID);
                    }
                }
            }
            $this->addData($resolvedObjectNestedPropertyRet, $nestedPropertyFields, $databases, $unionTypeOutputKeyIDs, $unionTypeOutputKeyID ?? $resolvedObject[$nestedFieldInstance], $nextField, $typeOutputKeyPaths);
        }
    }
}
