<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\FeedbackItemProviders;

use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider as UpstreamErrorFeedbackItemProvider;

class ErrorFeedbackItemProvider extends UpstreamErrorFeedbackItemProvider
{
    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E9 => $this->__('Object with ID \'%2$s\' (of type \'%1$s\') cannot be loaded. Please check if referencing this ID is stale data, still stored on the WordPress database, but pointing to a non-existing object.', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }
}
