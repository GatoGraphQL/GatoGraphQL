<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class SuggestionFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const S1 = 's1';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::S1,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::S1 => $this->__('To execute multiple queries in a single request, add the following operation to the GraphQL query, and execute it: `query %s { id }`', 'graphql-parser'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::SUGGESTION;
    }
}
