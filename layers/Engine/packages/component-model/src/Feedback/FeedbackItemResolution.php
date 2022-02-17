<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\FeedbackItemProviders\FeedbackItemProviderInterface;
use PoP\Root\Services\StandaloneServiceTrait;

class FeedbackItemResolution
{
    use StandaloneServiceTrait;

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

    final public function getFeedbackItemProvider(): FeedbackItemProviderInterface
    {
        return InstanceManagerFacade::getInstance()->getInstance($this->feedbackProviderServiceClass);
    }

    final public function getMessage(): string
    {
        $feedbackItemProvider = $this->getFeedbackItemProvider();
        return $feedbackItemProvider->getMessage($this->code, ...$this->messageParams);
    }
}
