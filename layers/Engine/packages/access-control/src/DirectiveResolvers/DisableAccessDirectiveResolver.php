<?php

declare(strict_types=1);

namespace PoP\AccessControl\DirectiveResolvers;

use PoP\AccessControl\FeedbackItemProviders\FeedbackItemProvider;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

class DisableAccessDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'disableAccess';
    }

    protected function isValidationSuccessful(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return false;
    }

    /**
     * Add the errors to the FeedbackStore
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    protected function addUnsuccessfulValidationErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $fieldIDs = MethodHelpers::orderIDsByDirectFields($idFieldSet);
        /** @var FieldInterface $field */
        foreach ($fieldIDs as $field) {
            /** @var array<string|int> */
            $ids = $fieldIDs[$field];
            $engineIterationFeedbackStore->objectFeedbackStore->addError(
                new ObjectResolutionFeedback(
                    new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        $this->isValidatingDirective() ? FeedbackItemProvider::E1 : FeedbackItemProvider::E2,
                        [
                            $field->asFieldOutputQueryString(),
                            $relationalTypeResolver->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $field,
                    $relationalTypeResolver,
                    $this->directive,
                    $this->getFieldIDSetForField($field, $ids),
                )
            );
        }
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('It disables access to the field', 'access-control');
    }
}
