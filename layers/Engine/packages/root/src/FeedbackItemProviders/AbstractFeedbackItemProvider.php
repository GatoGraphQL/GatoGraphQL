<?php

declare(strict_types=1);

namespace PoP\Root\FeedbackItemProviders;

use ArgumentCountError;
use PoP\Root\Exception\MisconfiguredServiceException;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractFeedbackItemProvider implements FeedbackItemProviderInterface
{
    use BasicServiceTrait;

    final public function getNamespacedCode(string $code): string
    {
        return $this->getNamespace() . $this->getNamespaceSeparator() . $code;
    }

    protected function getNamespace(): string
    {
        return str_replace(
            '\\',
            '/',
            ClassHelpers::getClassPSR4Namespace(\get_called_class())
        );
    }

    protected function getNamespaceSeparator(): string
    {
        return '@';
    }

    final public function getMessage(string $code, string|int|float|bool|null ...$args): string
    {
        /**
         * Soft landing: If there's an error in passing arguments,
         * then print the placeholder as the error message, and
         * avoid throwing an exception.
         */
        $messagePlaceholder = $this->getMessagePlaceholder($code);
        try {
            return \sprintf(
                $messagePlaceholder,
                ...$args
            );
        } catch (ArgumentCountError $e) {
            return $messagePlaceholder;
        }
    }

    public function getMessagePlaceholder(string $code): string
    {
        throw new MisconfiguredServiceException(
            \sprintf(
                $this->__('There is no message placeholder for code \'%s\'', 'root'),
                $code
            )
        );
    }

    public function getCategory(string $code): string
    {
        throw new MisconfiguredServiceException(
            \sprintf(
                $this->__('There is no category for code \'%s\'', 'root'),
                $code
            )
        );
    }

    public function getSpecifiedByURL(string $code): ?string
    {
        return null;
    }
}
