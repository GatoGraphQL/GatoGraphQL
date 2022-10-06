<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;

class GraphQLUnsupportedFeatureErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E_1 = '1';
    public final const E_2 = '2';
    public final const E_3 = '3';

    protected function getNamespace(): string
    {
        return 'gqlunsupported';
    }

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E_1,
            self::E_2,
            self::E_3,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E_1 => $this->__('Subscriptions are currently not supported', 'graphql-server'),
            self::E_2 => $this->__('Fragment Definition Directives are currently not supported', 'graphql-server'),
            self::E_3 => $this->__('Variable Definition Directives are currently not supported', 'graphql-server'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
