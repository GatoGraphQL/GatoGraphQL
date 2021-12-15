<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\Dataloading\Expressions;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;

class ForEachDirectiveResolver extends AbstractApplyNestedDirectivesOnArrayOrObjectItemsDirectiveResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

    public function getDirectiveName(): string
    {
        return 'forEach';
    }

    public function getDirectiveKind(): string
    {
        return DirectiveKinds::INDEXING;
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->getTranslationAPI()->__('Iterate all affected array items and execute the composed directives on them', 'component-model');
    }

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return array_merge(
            parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver),
            [
                'if' => $this->getBooleanScalarTypeResolver(),
            ]
        );
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'if' => $this->getTranslationAPI()->__('If provided, iterate only those items that satisfy this condition `%s`', 'component-model'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveExpressions(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            Expressions::NAME_KEY => $this->getTranslationAPI()->__('Key of the array element from the current iteration', 'component-model'),
            Expressions::NAME_VALUE => $this->getTranslationAPI()->__('Value of the array element from the current iteration', 'component-model'),
        ];
    }

    /**
     * Iterate on all items from the array
     */
    protected function getArrayItems(array &$array, int | string $id, string $field, RelationalTypeResolverInterface $relationalTypeResolver, array &$objectIDItems, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$objectErrors, array &$objectWarnings, array &$objectDeprecations): ?array
    {
        if ($if = $this->directiveArgsForSchema['if'] ?? null) {
            // If it is a field, execute the function against all the values in the array
            // Those that satisfy the condition stay, the others are filtered out
            // We must add each item in the array as expression `%{value}%`, over which the if function can be evaluated
            if ($this->getFieldQueryInterpreter()->isFieldArgumentValueAField($if)) {
                $options = [
                    AbstractRelationalTypeResolver::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM => true,
                ];
                $arrayItems = [];
                foreach ($array as $key => $value) {
                    $this->addExpressionForObject($id, Expressions::NAME_KEY, $key, $messages);
                    $this->addExpressionForObject($id, Expressions::NAME_VALUE, $value, $messages);
                    $expressions = $this->getExpressionsForObject($id, $variables, $messages);
                    $resolvedValue = $relationalTypeResolver->resolveValue($objectIDItems[(string)$id], $if, $variables, $expressions, $options);
                    // Merge the objectWarnings, if any
                    if ($storedObjectWarnings = $this->getFeedbackMessageStore()->retrieveAndClearObjectWarnings($id)) {
                        $objectWarnings[$id] = array_merge(
                            $objectWarnings[$id] ?? [],
                            $storedObjectWarnings
                        );
                    }
                    if (GeneralUtils::isError($resolvedValue)) {
                        // Show the error message, and return nothing
                        /** @var Error */
                        $error = $resolvedValue;
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->getTranslationAPI()->__('Executing field \'%s\' on object with ID \'%s\' produced error: %s. Setting expression \'%s\' was ignored', 'pop-component-model'),
                                $field,
                                $id,
                                $error->getMessageOrCode(),
                                $key
                            ),
                        ];
                        continue;
                    }
                    // Evaluate it
                    if ($resolvedValue) {
                        $arrayItems[$key] = $value;
                    }
                }
                return $arrayItems;
            }
        }
        return $array;
    }
}
