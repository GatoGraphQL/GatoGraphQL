<?php

declare(strict_types=1);

namespace PoP\Root\Feedback;

class FeedbackResolution
{
    public function __construct(
        protected string $feedbackProviderServiceClass,
        protected string $code,
        /** @var array<string|int|float|bool> */
        protected array $messageParams,
    ) { 
    }

    public function getFeedbackProviderServiceClass(): string
    {
        return $this->feedbackProviderServiceClass;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return array<string|int|float|bool>
     */
    public function getMessageParams(): array
    {
        return $this->messageParams;
    }
}
