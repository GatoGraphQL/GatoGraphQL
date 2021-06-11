<?php

declare(strict_types=1);

namespace PoP\API\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\TypeResolvers\UnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;

class CopyRelationalResultsDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'copyRelationalResults';
    }

    /**
     * This is a "Scripting" type directive
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SCRIPTING;
    }

    /**
     * Do not allow dynamic fields
     */
    protected function disableDynamicFieldsFromDirectiveArgs(): bool
    {
        return true;
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        return $this->translationAPI->__('Copy the data from a relational object (which is one level below) to the current object', 'component-model');
    }

    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'copyFromFields',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_IS_ARRAY => true,
                SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The fields in the relational object from which to copy the data', 'component-model'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
            [
                SchemaDefinition::ARGNAME_NAME => 'copyToFields',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_IS_ARRAY => true,
                SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The fields in the current object to which copy the data. Default value: Same fields provided through \'copyFromFields\' argument', 'component-model'),
            ],
            [
                SchemaDefinition::ARGNAME_NAME => 'keepRelationalIDs',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Indicate if the properties are placed under the relational ID as keys (`true`) or as a one-dimensional array (`false`)', 'component-model'),
                SchemaDefinition::ARGNAME_DEFAULT_VALUE => false,
            ],
        ];
    }

    /**
     * Validate that the number of elements in the fields `copyToFields` and `copyFromFields` match one another
     */
    public function validateDirectiveArgumentsForSchema(TypeResolverInterface $typeResolver, string $directiveName, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        $directiveArgs = parent::validateDirectiveArgumentsForSchema($typeResolver, $directiveName, $directiveArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);

        if (isset($directiveArgs['copyToFields'])) {
            $copyToFields = $directiveArgs['copyToFields'];
            $copyFromFields = $directiveArgs['copyFromFields'];
            $copyToFieldsCount = count($copyToFields);
            $copyFromFieldsCount = count($copyFromFields);

            // Validate that both arrays have the same number of elements
            if ($copyToFieldsCount > $copyFromFieldsCount) {
                $schemaWarnings[] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => sprintf(
                        $this->translationAPI->__('Argument \'copyToFields\' has more elements than argument \'copyFromFields\', so the following fields have been ignored: \'%s\'', 'component-model'),
                        implode($this->translationAPI->__('\', \''), array_slice($copyToFields, $copyFromFieldsCount))
                    ),
                ];
            } elseif ($copyToFieldsCount < $copyFromFieldsCount) {
                $schemaWarnings[] = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => sprintf(
                        $this->translationAPI->__('Argument \'copyFromFields\' has more elements than argument \'copyToFields\', so the following fields will be copied to the destination object under their same field name: \'%s\'', 'component-model'),
                        implode($this->translationAPI->__('\', \''), array_slice($copyFromFields, $copyToFieldsCount))
                    ),
                ];
            }
        }

        return $directiveArgs;
    }

    /**
     * Copy the data under the relational object into the current object
     */
    public function resolveDirective(
        TypeResolverInterface $typeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
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
        $copyFromFields = $this->directiveArgsForSchema['copyFromFields'];
        $copyToFields = $this->directiveArgsForSchema['copyToFields'] ?? $copyFromFields;
        $keepRelationalIDs = $this->directiveArgsForSchema['keepRelationalIDs'];

        // From the typeResolver, obtain under what type the data for the current object is stored
        $dbKey = $typeResolver->getTypeOutputName();

        // Copy the data from each of the relational object fields to the current object
        for ($i = 0; $i < count($copyFromFields); $i++) {
            $copyFromField = $copyFromFields[$i];
            $copyToField = $copyToFields[$i] ?? $copyFromFields[$i];
            foreach ($idsDataFields as $id => $dataFields) {
                foreach ($dataFields['direct'] as $relationalField) {
                    // The data is stored under the field's output key
                    $relationalFieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($relationalField);
                    // Validate that the current object has `relationalField` property set
                    // Since we are fetching from a relational object (placed one level below in the iteration stack), the value could've been set only in a previous iteration
                    // Then it must be in $previousDBItems (it can't be in $dbItems unless set by chance, because the same IDs were involved for a possibly different query)
                    if (!array_key_exists($relationalFieldOutputKey, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                        if ($relationalFieldOutputKey != $relationalField) {
                            $dbErrors[(string)$id][] = [
                                Tokens::PATH => [$this->directive],
                                Tokens::MESSAGE => sprintf(
                                    $this->translationAPI->__('Field \'%s\' (under property \'%s\') hadn\'t been set for object with ID \'%s\', so no data can be copied', 'component-model'),
                                    $relationalField,
                                    $relationalFieldOutputKey,
                                    $id
                                ),
                            ];
                        } else {
                            $dbErrors[(string)$id][] = [
                                Tokens::PATH => [$this->directive],
                                Tokens::MESSAGE => sprintf(
                                    $this->translationAPI->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so no data can be copied', 'component-model'),
                                    $relationalField,
                                    $id
                                ),
                            ];
                        }
                        continue;
                    }

                    // If the destination field already exists, warn that it will be overriden
                    $isTargetValueInDBItems = array_key_exists($copyToField, $dbItems[(string)$id] ?? []);
                    if ($isTargetValueInDBItems || array_key_exists($copyToField, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                        $dbWarnings[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('The existing value for field \'%s\' from object with ID \'%s\' has been overriden: \'%s\'', 'component-model'),
                                $copyToField,
                                $id,
                                $isTargetValueInDBItems ?
                                    $dbItems[(string)$id][$copyToField] :
                                    $previousDBItems[$dbKey][(string)$id][$copyToField]
                            ),
                        ];
                    }
                    // Copy the properties into the array
                    $dbItems[(string)$id][$copyToField] = [];

                    // Obtain the DBKey under which the relationalField is stored in the database
                    $relationalTypeResolverClass = $typeResolver->resolveFieldTypeResolverClass($relationalField);
                    $relationalTypeResolver = $this->instanceManager->getInstance((string)$relationalTypeResolverClass);
                    $relationalDBKey = $relationalTypeResolver->getTypeOutputName();
                    $isUnionRelationalDBKey = UnionTypeHelpers::isUnionType($relationalDBKey);
                    if ($isUnionRelationalDBKey) {
                        // If the relational type data resolver is union, we must use the corresponding IDs from $unionDBKeyIDs, which contain the type in addition to the ID
                        $relationalIDs = $unionDBKeyIDs[$dbKey][(string)$id][$relationalFieldOutputKey];
                    } else {
                        // Otherwise, directly use the IDs from the object
                        $relationalIDs = $previousDBItems[$dbKey][(string)$id][$relationalFieldOutputKey];
                    }

                    // $relationalIDs can be an array of IDs, or a single item. In the latter case, copy the property directly. In the former one, copy it under an array,
                    // either with the ID of relational object as key, or as a normal one-dimension array using no particular keys
                    $copyStraight = false;
                    if (!is_array($relationalIDs)) {
                        $relationalIDs = [$relationalIDs];
                        $copyStraight = true;
                    }

                    foreach ($relationalIDs as $relationalID) {
                        // Validate that the source field has been set.
                        if (!array_key_exists($copyFromField, $previousDBItems[$relationalDBKey][(string)$relationalID] ?? [])) {
                            $dbErrors[(string)$id][] = [
                                Tokens::PATH => [$this->directive],
                                Tokens::MESSAGE => sprintf(
                                    $this->translationAPI->__('Field \'%s\' hadn\'t been set for object of entity \'%s\' and ID \'%s\', so no data can be copied', 'component-model'),
                                    $copyFromField,
                                    $relationalDBKey,
                                    $relationalID
                                ),
                            ];
                            continue;
                        }
                        if ($copyStraight) {
                            $dbItems[(string)$id][$copyToField] = $previousDBItems[$relationalDBKey][(string)$relationalID][$copyFromField];
                        } elseif ($keepRelationalIDs) {
                            $dbItems[(string)$id][$copyToField][(string)$relationalID] = $previousDBItems[$relationalDBKey][(string)$relationalID][$copyFromField];
                        } else {
                            $dbItems[(string)$id][$copyToField][] = $previousDBItems[$relationalDBKey][(string)$relationalID][$copyFromField];
                        }
                    }
                }
            }
        }
    }
}
