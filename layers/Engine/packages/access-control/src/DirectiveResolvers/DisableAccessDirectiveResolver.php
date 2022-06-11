<?php

declare(strict_types=1);

namespace PoP\AccessControl\DirectiveResolvers;

use PoP\AccessControl\FeedbackItemProviders\FeedbackItemProvider;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;
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
     * @param FieldInterface[] $failedDataFields
     */
    protected function getValidationFailedFeedbackItemResolution(RelationalTypeResolverInterface $relationalTypeResolver, array $failedDataFields): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            FeedbackItemProvider::class,
            $this->isValidatingDirective() ? FeedbackItemProvider::E1 : FeedbackItemProvider::E2,
            [
                implode(
                    $this->__('\', \''),
                    array_map(
                        fn (FieldInterface $field) => $field->asFieldOutputQueryString(),
                        $failedDataFields
                    )
                ),
            ]
        );
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('It disables access to the field', 'access-control');
    }
}
