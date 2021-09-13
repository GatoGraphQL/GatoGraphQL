<?php

declare(strict_types=1);

namespace PoP\API\DirectiveResolvers;

use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class RenamePropertyDirectiveResolver extends DuplicatePropertyDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'renameProperty';
    }

    /**
     * This is a "Scripting" type directive
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SCRIPTING;
    }

    public function getSchemaDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->translationAPI->__('Rename a property in the current object', 'component-model');
    }

    /**
     * Rename a property from the current object
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$objectIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        // After duplicating the property, delete the original
        parent::resolveDirective(
            $relationalTypeResolver,
            $idsDataFields,
            $succeedingPipelineIDsDataFields,
            $succeedingPipelineDirectiveResolverInstances,
            $objectIDItems,
            $unionDBKeyIDs,
            $dbItems,
            $previousDBItems,
            $variables,
            $messages,
            $objectErrors,
            $objectWarnings,
            $objectDeprecations,
            $objectNotices,
            $objectTraces,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces
        );
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                /**
                 * The data is stored under the field's output key (not the unique one!)
                 */
                $fieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($field);
                unset($dbItems[(string)$id][$fieldOutputKey]);
            }
        }
    }
}
