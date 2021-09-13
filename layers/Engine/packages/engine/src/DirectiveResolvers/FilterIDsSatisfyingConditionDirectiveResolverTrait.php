<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\RemoveIDsDataFieldsDirectiveResolverTrait;

trait FilterIDsSatisfyingConditionDirectiveResolverTrait
{
    use RemoveIDsDataFieldsDirectiveResolverTrait;

    protected function getIdsSatisfyingCondition(RelationalTypeResolverInterface $relationalTypeResolver, array &$resultIDItems, array &$idsDataFields, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations)
    {
        // Check the condition field. If it is satisfied, then skip those fields
        $idsSatisfyingCondition = [];
        foreach (array_keys($idsDataFields) as $id) {
            // Validate directive args for the object
            $expressions = $this->getExpressionsForObject($id, $variables, $messages);
            $object = $resultIDItems[$id];
            list(
                $objectValidDirective,
                $objectDirectiveName,
                $objectDirectiveArgs
            ) = $this->dissectAndValidateDirectiveForObject($relationalTypeResolver, $object, $variables, $expressions, $dbErrors, $dbWarnings, $dbDeprecations);
            // Check that the directive is valid. If it is not, $dbErrors will have the error already added
            if (is_null($objectValidDirective)) {
                continue;
            }
            // $objectDirectiveArgs has all the right directiveArgs values. Now we can evaluate on it
            if ($objectDirectiveArgs['if'] ?? null) {
                $idsSatisfyingCondition[] = $id;
            }
        }
        return $idsSatisfyingCondition;
    }

    protected function removeDataFieldsForIDs(array &$idsDataFields, array &$idsToRemove, array &$succeedingPipelineIDsDataFields)
    {
        // Calculate the $idsDataFields that must be removed from all the upcoming stages of the pipeline
        $idsDataFieldsToRemove = array_filter(
            $idsDataFields,
            fn (int | string $id) => in_array($id, $idsToRemove),
            ARRAY_FILTER_USE_KEY
        );
        $this->removeIDsDataFields(
            $idsDataFieldsToRemove,
            $succeedingPipelineIDsDataFields
        );
    }
}
