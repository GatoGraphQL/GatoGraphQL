<?php

declare(strict_types=1);

namespace PoP\APIMirrorQuery\DataStructureFormatters;

use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\ComponentModel\DataStructure\AbstractJSONDataStructureFormatter;
use PoP\ComponentModel\State\ApplicationState;

class MirrorQueryDataStructureFormatter extends AbstractJSONDataStructureFormatter
{
    public function getName(): string
    {
        return 'mirrorquery';
    }

    protected function getFields()
    {
        // Allow REST to override with default fields
        $vars = ApplicationState::getVars();
        return $vars['requested-query'] ?? $vars['query'] ?? [];
    }

    public function getFormattedData($data)
    {
        // Re-create the shape of the query by iterating through all objectIDs and all required fields,
        // getting the data from the corresponding dbKeyPath
        $ret = [];
        if ($fields = $this->getFields()) {
            $databases = $data['dbData'] ?? [];
            $unionDBKeyIDs = $data['unionDBKeyIDs'] ?? [];
            $datasetModuleData = $data['datasetmoduledata'] ?? [];
            foreach ($datasetModuleData as $moduleName => $objectIDs) {
                $dbKeyPaths = $data['datasetmodulesettings'][$moduleName]['dbkeys'] ?? [];
                $objectIDorIDs = $objectIDs['dbobjectids'];
                $this->addData($ret, $fields, $databases, $unionDBKeyIDs, $objectIDorIDs, 'id', $dbKeyPaths, false);
            }
        }

        return $ret;
    }
    // GraphQL/REST cannot have getExtraRoutes()!!!!! Because the fields can't be applied to different resources! (Eg: author/leo/ and author/leo/?route=posts)
    // public function getFormattedData($data)
    // {
    //     // Re-create the shape of the query by iterating through all objectIDs and all required fields,
    //     // getting the data from the corresponding dbKeyPath
    //     $ret = [];
    //     if ($fields = $this->getFields()) {
    //         $engine = EngineFacade::getInstance();
    //         list($has_extra_routes) = $engine->listExtraRouteVars();
    //         $vars = ApplicationState::getVars();
    //         $dataoutputmode = $vars['dataoutputmode'];

    //         $databases = $data['dbData'] ?? [];
    //         $datasetModuleData = $data['datasetmoduledata'] ?? [];
    //         $datasetModuleSettings = $data['datasetmodulesettings'] ?? [];
    //         if ($dataoutputmode == \PoP\ComponentModel\Constants\DataOutputModes::SPLITBYSOURCES) {
    //             if ($has_extra_routes) {
    //                 $datasetModuleData = array_merge_recursive(
    //                     $datasetModuleData['immutable'] ?? [],
    //                     ($has_extra_routes ? array_values($datasetModuleData['mutableonmodel'])[0] : $datasetModuleData['mutableonmodel']) ?? [],
    //                     ($has_extra_routes ? array_values($datasetModuleData['mutableonrequest'])[0] : $datasetModuleData['mutableonrequest']) ?? []
    //                 );
    //                 $datasetModuleSettings = array_merge_recursive(
    //                     $datasetModuleSettings['immutable'] ?? [],
    //                     ($has_extra_routes ? array_values($datasetModuleSettings['mutableonmodel'])[0] : $datasetModuleSettings['mutableonmodel']) ?? [],
    //                     ($has_extra_routes ? array_values($datasetModuleSettings['mutableonrequest'])[0] : $datasetModuleSettings['mutableonrequest']) ?? []
    //                 );
    //             }
    //         } elseif ($dataoutputmode == \PoP\ComponentModel\Constants\DataOutputModes::COMBINED) {
    //             if ($has_extra_routes) {
    //                 $datasetModuleData = array_values($datasetModuleData)[0];
    //                 $datasetModuleSettings = array_values($datasetModuleSettings)[0];
    //             }
    //         }
    //         foreach ($datasetModuleData as $moduleName => $objectIDs) {
    //             $dbKeyPaths = $datasetModuleSettings[$moduleName]['dbkeys'] ?? [];
    //             $objectIDorIDs = $objectIDs['dbobjectids'];
    //             $this->addData($ret, $fields, $databases, $objectIDorIDs, 'id', $dbKeyPaths, false);
    //         }
    //     }

    //     return $ret;
    // }

    protected function addData(&$ret, $fields, &$databases, &$unionDBKeyIDs, $objectIDorIDs, $objectKeyPath, &$dbKeyPaths, $concatenateField = true)
    {
        // Property fields have numeric key only. From them, obtain the fields to print for the object
        $propertyFields = array_filter(
            $fields,
            function ($key) {
                return is_numeric($key);
            },
            ARRAY_FILTER_USE_KEY
        );
        // All other fields must be nested, to keep fetching data for the object relationships
        $nestedFields = array_filter(
            $fields,
            function ($key) {
                return !is_numeric($key);
            },
            ARRAY_FILTER_USE_KEY
        );

        // The results can be a single ID or value, or an array of IDs
        if (is_array($objectIDorIDs)) {
            foreach ($objectIDorIDs as $objectID) {
                // Add a new array for this DB object, where to return all its properties
                $ret[] = [];
                $dbObjectRet = &$ret[count($ret) - 1];
                $this->addDBObjectData($dbObjectRet, $propertyFields, $nestedFields, $databases, $unionDBKeyIDs, $objectID, $objectKeyPath, $dbKeyPaths, $concatenateField);
            }
        } else {
            $objectID = $objectIDorIDs;
            $this->addDBObjectData($ret, $propertyFields, $nestedFields, $databases, $unionDBKeyIDs, $objectID, $objectKeyPath, $dbKeyPaths, $concatenateField);
        }
    }

    protected function addDBObjectData(&$dbObjectRet, $propertyFields, $nestedFields, &$databases, &$unionDBKeyIDs, $objectID, $objectKeyPath, &$dbKeyPaths, $concatenateField): void
    {
        // If there are no property fields and no nestedFields, then do nothing.
        // Otherwise, it could throw an error on `extractDBObjectTypeAndID`
        // because it only has the ID and not the name of the type
        // Eg: When a validation on the last field fails, such as: /?query=posts.id(
        if (!$propertyFields && !$nestedFields) {
            return;
        }
        // Execute for all fields other than the first one, "root", for both UnionTypeResolvers and non-union ones
        // This is because if it's a relational field that comes after a UnionTypeResolver, its dbKey could not be inferred (since it depends from the dbObject, and can't be obtained in the settings, where "dbkeys" is obtained and which doesn't depend on data items)
        // Eg: /?query=content.comments.id. In this case, "content" is handled by UnionTypeResolver, and "comments" would not be found since its entry can't be added under "datasetmodulesettings.dbkeys", since the module (of class AbstractRelationalFieldQueryDataModuleProcessor) with a UnionTypeResolver can't resolve the 'succeeding-typeResolver' to set to its submodules
        // if (UnionTypeHelpers::isUnionType($dbKey))
        if ($concatenateField) {
            list(
                $dbKey,
                $objectID
            ) = UnionTypeHelpers::extractDBObjectTypeAndID(
                // If the object could not be loaded, $objectID will be all ID, with no $dbKey
                // Since that could be an int, the strict typing would throw an error,
                // so make sure to type it as a string
                (string) $objectID
            );
        } else {
            // Add all properties requested from the object
            $dbKey = $dbKeyPaths[$objectKeyPath];
        }
        // If there is no dbKey, it is an error (eg: requesting posts.cats.saranga)
        if (!$dbKey) {
            return;
        }

        $dbObject = $databases[$dbKey][$objectID] ?? [];
        foreach ($propertyFields as $propertyField) {
            // Only if the property has been set (in case of dbError it is not set)
            $propertyFieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($propertyField);
            $uniquePropertyFieldOutputKey = $this->fieldQueryInterpreter->getUniqueFieldOutputKeyByTypeOutputName($dbKey, $propertyField);
            if (array_key_exists($uniquePropertyFieldOutputKey, $dbObject)) {
                $dbObjectRet[$propertyFieldOutputKey] = $dbObject[$uniquePropertyFieldOutputKey];
            }
        }

        // Add the nested levels
        foreach ($nestedFields as $nestedField => $nestedPropertyFields) {
            $nestedFieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($nestedField);
            $uniqueNestedFieldOutputKey = $this->fieldQueryInterpreter->getUniqueFieldOutputKeyByTypeOutputName($dbKey, $nestedField);
            // If the key doesn't exist, then do nothing. This supports the "skip output if null" behaviour: if it is to be skipped, there will be no value (which is different than a null)
            if (array_key_exists($uniqueNestedFieldOutputKey, $dbObject)) {
                // If it's null, directly assign the null to the result
                if (is_null($dbObject[$uniqueNestedFieldOutputKey])) {
                    $dbObjectRet[$nestedFieldOutputKey] = null;
                } else {
                    // Watch out! If the property has already been loaded from a previous iteration, in some cases it can create trouble!
                    // But make sure that there truly are subproperties! It could also be a schemaError.
                    // Eg: ?query=posts.title.id, then no need to transform "title" from string to {"id" => ...}
                    if ($this->feedbackMessageStore->getSchemaErrorsForField($dbKey, $nestedField)) {
                        $dbObjectRet[$nestedFieldOutputKey] = $dbObject[$uniqueNestedFieldOutputKey];
                    } else {
                        // The first field, "id", needs not be concatenated. All the others do need
                        $nextField = ($concatenateField ? $objectKeyPath . '.' : '') . $uniqueNestedFieldOutputKey;

                        // The type with ID may be stored under $unionDBKeyIDs
                        $unionDBKeyID = $unionDBKeyIDs[$dbKey][$objectID][$uniqueNestedFieldOutputKey] ?? null;

                        // Add a new subarray for the nested property
                        $dbObjectNestedPropertyRet = &$dbObjectRet[$nestedFieldOutputKey];

                        // If it is an empty array, then directly add an empty array as the result
                        if (is_array($dbObject[$uniqueNestedFieldOutputKey]) && empty($dbObject[$uniqueNestedFieldOutputKey])) {
                            $dbObjectRet[$nestedFieldOutputKey] = [];
                        } else {
                            if (!empty($dbObjectNestedPropertyRet)) {
                                // 1. If we load a relational property as its ID, and then load properties on the corresponding object, then it will fail because it will attempt to add a property to a non-array element
                                // Eg: /posts/api/graphql/?query=id|author,author.name will first return "author => 1" and on the "1" element add property "name"
                                // Then, if this situation happens, simply override the ID (which is a scalar value, such as an int or string) with an object with the 'id' property
                                if (!is_array($dbObjectNestedPropertyRet)) {
                                    $dbObjectRet[$nestedFieldOutputKey] = [
                                        'id' => $dbObjectRet[$nestedFieldOutputKey],
                                    ];
                                } else {
                                    // 2. If the previous iteration loaded an array of IDs, then override this value with an empty array and initialize the ID again to this object, through adding property 'id' on the next iteration
                                    // Eg: /api/graphql/?query=tags,tags.name
                                    $dbObjectRet[$nestedFieldOutputKey] = [];
                                    if (!in_array('id', $nestedPropertyFields)) {
                                        array_unshift($nestedPropertyFields, 'id');
                                    }
                                }
                            }
                            $this->addData($dbObjectNestedPropertyRet, $nestedPropertyFields, $databases, $unionDBKeyIDs, $unionDBKeyID ?? $dbObject[$uniqueNestedFieldOutputKey], $nextField, $dbKeyPaths);
                        }
                    }
                }
            }
        }
    }
}
