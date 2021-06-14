<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\Dataloading\Expressions;

class ForEachDirectiveResolver extends AbstractApplyNestedDirectivesOnArrayItemsDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'forEach';
    }

    /**
     * This is a "Scripting" type directive
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SCRIPTING;
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        return $this->translationAPI->__('Iterate all affected array items and execute the composed directives on them', 'component-model');
    }

    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        return array_merge(
            parent::getSchemaDirectiveArgs($typeResolver),
            [
                [
                    SchemaDefinition::ARGNAME_NAME => 'if',
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('If provided, iterate only those items that satisfy this condition `%s`', 'component-model'),
                ],
            ]
        );
    }

    public function getSchemaDirectiveExpressions(TypeResolverInterface $typeResolver): array
    {
        return [
            Expressions::NAME_KEY => $this->translationAPI->__('Key of the array element from the current iteration', 'component-model'),
            Expressions::NAME_VALUE => $this->translationAPI->__('Value of the array element from the current iteration', 'component-model'),
        ];
    }

    /**
     * Iterate on all items from the array
     */
    protected function getArrayItems(array &$array, int | string $id, string $field, TypeResolverInterface $typeResolver, array &$resultIDItems, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$dbErrors, array &$dbWarnings, array &$dbDeprecations): ?array
    {
        if ($if = $this->directiveArgsForSchema['if'] ?? null) {
            // If it is a field, execute the function against all the values in the array
            // Those that satisfy the condition stay, the others are filtered out
            // We must add each item in the array as expression `%value%`, over which the if function can be evaluated
            if ($this->fieldQueryInterpreter->isFieldArgumentValueAField($if)) {
                $options = [
                    AbstractTypeResolver::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM => true,
                ];
                $arrayItems = [];
                foreach ($array as $key => $value) {
                    $this->addExpressionForResultItem($id, Expressions::NAME_KEY, $key, $messages);
                    $this->addExpressionForResultItem($id, Expressions::NAME_VALUE, $value, $messages);
                    $expressions = $this->getExpressionsForResultItem($id, $variables, $messages);
                    $resolvedValue = $typeResolver->resolveValue($resultIDItems[(string)$id], $if, $variables, $expressions, $options);
                    // Merge the dbWarnings, if any
                    if ($resultItemDBWarnings = $this->feedbackMessageStore->retrieveAndClearResultItemDBWarnings($id)) {
                        $dbWarnings[$id] = array_merge(
                            $dbWarnings[$id] ?? [],
                            $resultItemDBWarnings
                        );
                    }
                    if (GeneralUtils::isError($resolvedValue)) {
                        // Show the error message, and return nothing
                        /** @var Error */
                        $error = $resolvedValue;
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('Executing field \'%s\' on object with ID \'%s\' produced error: %s. Setting expression \'%s\' was ignored', 'pop-component-model'),
                                $value,
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
