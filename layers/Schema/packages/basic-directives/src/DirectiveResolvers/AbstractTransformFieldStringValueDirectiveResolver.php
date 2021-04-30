<?php

declare(strict_types=1);

namespace PoPSchema\BasicDirectives\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;

/**
 * Apply a transformation to the string
 */
abstract class AbstractTransformFieldStringValueDirectiveResolver extends AbstractTransformFieldValueDirectiveResolver
{
    protected function validateTypeIsString($value, $id, string $field, string $fieldOutputKey, array &$dbErrors, array &$dbWarnings)
    {
        if (!is_string($value)) {
            $dbWarnings[(string)$id][] = [
                Tokens::PATH => [$this->directive],
                Tokens::MESSAGE => sprintf(
                    $this->translationAPI->__('Directive \'%s\' from field \'%s\' cannot be applied on object with ID \'%s\' because it is not a string', 'practical-directives'),
                    $this->getDirectiveName(),
                    $fieldOutputKey,
                    $id
                ),
            ];
            return false;
        }
        return true;
    }
}
