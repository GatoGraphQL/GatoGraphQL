<?php

declare(strict_types=1);

namespace PoPSchema\GoogleTranslateDirective\DirectiveResolvers;

use PoP\ComponentModel\ErrorHandling\Error;
use PoPSchema\GoogleTranslateDirective\Environment;
use PoPSchema\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;

abstract class AbstractGoogleTranslateDirectiveResolver extends AbstractTranslateDirectiveResolver
{
    const PROVIDERS_GOOGLE = 'google';

    /**
     * The name of the API's provider
     */
    public function getProvidersToResolve(): array
    {
        return [
            self::PROVIDERS_GOOGLE,
        ];
    }

    protected function getClientFailureMessage(Error $error, string $provider): string
    {
        // Remove the API key from being printed
        return str_replace(
            $this->getEndpoint($provider),
            $this->getEndpointURL($provider),
            parent::getClientFailureMessage($error, $provider)
        );
    }

    protected function getAPIKey(string $provider)
    {
        switch ($provider) {
            case self::PROVIDERS_GOOGLE:
                if ($apiKey = Environment::getGoogleTranslateAPIKey()) {
                    return $apiKey;
                }
                break;
        }
        return null;
    }

    protected function getEndpointURL(string $provider): ?string
    {
        switch ($provider) {
            case self::PROVIDERS_GOOGLE:
                return 'https://translation.googleapis.com/language/translate/v2';
        }
        return null;
    }

    protected function getEndpoint(string $provider): ?string
    {
        switch ($provider) {
            case self::PROVIDERS_GOOGLE:
                $endpointURL = $this->getEndpointURL($provider);
                $apiKey = $this->getAPIKey($provider);
                if ($endpointURL && $apiKey) {
                    return sprintf(
                        '%s?key=%s',
                        $endpointURL,
                        $apiKey
                    );
                }
                break;
        }
        return null;
    }

    protected function getQuery(string $provider, string $sourceLang, string $targetLang, array $contents): array
    {
        switch ($provider) {
            case self::PROVIDERS_GOOGLE:
                return [
                    'format' => 'text',
                    'source' => $sourceLang,
                    'target' => $targetLang,
                    'q' => $contents,
                ];
        }
        return parent::getQuery($provider, $sourceLang, $targetLang, $contents);
    }

    protected function getErrorMessageFromResponse(string $provider, array $response): ?string
    {
        switch ($provider) {
            case self::PROVIDERS_GOOGLE:
                // An array with the translations is found under data/translations
                if (!$response['data']) {
                    return $this->translationAPI->__('There was an unidentified error when fetching data from the Google Translate API', 'google-translate-directive');
                }
        }
        return parent::getErrorMessageFromResponse($provider, $response);
    }

    protected function extractTranslationsFromResponse(string $provider, array $response): array
    {
        switch ($provider) {
            case self::PROVIDERS_GOOGLE:
                // An array with the translations is found under data/translations
                $translations = $response['data']['translations'];
                // Each response comes after key "translatedText". Remove it from there
                // Ref: https://cloud.google.com/translate/docs/reference/rest/v2/translate
                return array_map(
                    function ($entry) {
                        return $entry['translatedText'];
                    },
                    $translations
                );
        }
        return parent::extractTranslationsFromResponse($provider, $response);
    }
}
