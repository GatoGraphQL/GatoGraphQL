<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;

class GraphQLExtendedSpecFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = '1';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('Context has not been set for dynamic variable reference \'%s\'', 'graphql-server'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1 => FeedbackCategories::ERROR,
            default => parent::getCategory($code),
        };
    }
}
