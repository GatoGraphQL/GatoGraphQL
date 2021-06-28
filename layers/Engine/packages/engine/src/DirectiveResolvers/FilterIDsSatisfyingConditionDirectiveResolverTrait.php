<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\RemoveIDsDataFieldsDirectiveResolverTrait;

trait FilterIDsSatisfyingConditionDirectiveResolverTrait
{
    use RemoveIDsDataFieldsDirectiveResolverTrait;

    protected function getIdsSatisfyingCondition(TypeResolverInterface $typeResolver, array &$resultIDItems, array &$idsDataFields, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations)
    {
        // Check the condition field. If it is satisfied, then skip those fields
        $idsSatisfyingCondition = [];
        foreach (array_keys($idsDataFields) as $id) {
            // Validate directive args for the resultItem
            $expressions = $this->getExpressionsForResultItem($id, $variables, $messages);
            $resultItem = $resultIDItems[$id];
            list(
                $resultItemValidDirective,
                $resultItemDirectiveName,
                $resultItemDirectiveArgs
            ) = $this->dissectAndValidateDirectiveForResultItem($typeResolver, $resultItem, $variables, $expressions, $dbErrors, $dbWarnings, $dbDeprecations);
            // Check that the directive is valid. If it is not, $dbErrors will have the error already added
            if (is_null($resultItemValidDirective)) {
                continue;
            }
            // $resultItemDirectiveArgs has all the right directiveArgs values. Now we can evaluate on it
            if ($resultItemDirectiveArgs['if'] ?? null) {
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
