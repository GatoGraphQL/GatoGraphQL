<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\FieldQuery\FieldQueryInterpreter as UpstreamFieldQueryInterpreter;

class FieldQueryInterpreter extends UpstreamFieldQueryInterpreter implements FieldQueryInterpreterInterface
{
    public function resolveExpression(
        RelationalTypeResolverInterface $relationalTypeResolver,
        mixed $fieldArgValue,
        ?array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // Expressions: allow to pass a field argument "key:%input%", which is passed when executing the directive through $expressions

        /**
         * Switched from "%{...}%" to "$__..."
         * @todo Convert expressions from "$__" to "$"
         */
        // // Trim it so that "%{ self }%" is equivalent to "%{self}%". This is needed to set expressions through Symfony's DependencyInjection component (since %...% is reserved for its own parameters!)
        // $expressionName = trim(substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING), strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING) - strlen(QuerySyntax::SYMBOL_EXPRESSION_CLOSING)));
        $expressionName = substr($fieldArgValue, strlen('$__'));
        if (!isset($expressions[$expressionName])) {
            // If the expression is not set, then show the error under entry "expressionErrors"
            // @todo Temporarily hack fix: Need to pass astNode but don't have it, so commented
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             ErrorFeedbackItemProvider::class,
            //             ErrorFeedbackItemProvider::E14,
            //             [
            //                 $expressionName,
            //             ]
            //         ),
            //         ASTNodesFactory::getNonSpecificLocation(),
            //         $relationalTypeResolver,
            //     )
            // );
            return null;
        }
        return $expressions[$expressionName];
    }
}
