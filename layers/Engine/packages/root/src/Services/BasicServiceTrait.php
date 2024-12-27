<?php

declare(strict_types=1);

namespace PoP\Root\Services;

use PoP\Root\Instances\InstanceManagerInterface;
use PoP\Root\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait BasicServiceTrait
{
    protected InstanceManagerInterface $instanceManager;
    private ?TranslationAPIInterface $translationAPI = null;

    #[Required]
    final public function setInstanceManager(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }
    final protected function getInstanceManager(): InstanceManagerInterface
    {
        return $this->instanceManager;
    }

    final protected function getTranslationAPI(): TranslationAPIInterface
    {
        if ($this->translationAPI === null) {
            /** @var TranslationAPIInterface */
            $translationAPI = $this->instanceManager->getInstance(TranslationAPIInterface::class);
            $this->translationAPI = $translationAPI;
        }
        return $this->translationAPI;
    }

    /**
     * Shortcut function
     */
    final protected function __(string $text, string $domain = 'default'): string
    {
        return $this->getTranslationAPI()->__($text, $domain);
    }
}
