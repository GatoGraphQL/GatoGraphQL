<?php

declare(strict_types=1);

namespace PoPSchema\BasicDirectives\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractSchemaDirectiveResolver;

/**
 * Apply a transformation to the string
 */
abstract class AbstractTransformFieldValueDirectiveResolver extends AbstractSchemaDirectiveResolver
{
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
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                $dbItems[(string)$id][$fieldOutputKey] = $this->transformValue(
                    $dbItems[(string)$id][$fieldOutputKey],
                    $id,
                    $field,
                    $fieldOutputKey,
                    $typeResolver,
                    $variables,
                    $messages,
                    $dbErrors,
                    $dbWarnings,
                    $dbDeprecations,
                    $schemaErrors,
                    $schemaWarnings,
                    $schemaDeprecations
                );
            }
        }
    }

    abstract protected function transformValue($value, $id, string $field, string $fieldOutputKey, TypeResolverInterface $typeResolver, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations);
}
