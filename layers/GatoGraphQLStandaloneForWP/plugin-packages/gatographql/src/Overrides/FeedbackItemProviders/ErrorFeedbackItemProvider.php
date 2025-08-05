<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\FeedbackItemProviders;

use GatoGraphQLStandalone\GatoGraphQL\Module;
use GatoGraphQLStandalone\GatoGraphQL\ModuleConfiguration;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider as UpstreamErrorFeedbackItemProvider;

class ErrorFeedbackItemProvider extends UpstreamErrorFeedbackItemProvider
{
    public function getMessagePlaceholder(string $code): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return match ($code) {
            self::E9 => $moduleConfiguration->overrideErrorFeedbackItemProviderObjectNotFoundErrorMessage()
                ? $this->__('Object with ID \'%2$s\' (of type \'%1$s\') cannot be loaded. Please check if referencing this ID is stale data (i.e. still stored on the WordPress database, but pointing to a non-existing object) and, if so, remove it or fix it.', 'component-model')
                : parent::getMessagePlaceholder($code),
            default => parent::getMessagePlaceholder($code),
        };
    }
}
