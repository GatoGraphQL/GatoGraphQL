<?php

declare(strict_types=1);

namespace PoP\Root\Services;

use PoP\Root\Instances\InstanceManagerInterface;
use PoP\Root\Translation\TranslationAPIInterface;

abstract class AbstractBasicService implements BasicServiceInterface
{
    protected InstanceManagerInterface $instanceManager;
    private ?TranslationAPIInterface $translationAPI = null;

    /**
     * Injecting the InstanceManager service is mandatory, always.
     * It was originally done like this:
     *
     *   #[Required]
     *
     * which was downgraded to:
     *
     *   @required
     *
     * However it doesn't always work! So instead inject
     * the InstanceManager via a CompilerPass
     *
     * @see https://github.com/GatoGraphQL/GatoGraphQL/pull/3009
     */
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
