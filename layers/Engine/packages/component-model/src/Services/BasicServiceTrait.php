<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Services;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait BasicServiceTrait
{
    protected InstanceManagerInterface $instanceManager;
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    public function setInstanceManager(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }
    protected function getInstanceManager(): InstanceManagerInterface
    {
        return $this->instanceManager;
    }

    #[Required]
    public function setTranslationAPI(TranslationAPIInterface $translationAPI): void
    {
        $this->translationAPI = $translationAPI;
    }
    protected function getTranslationAPI(): TranslationAPIInterface
    {
        return $this->translationAPI;
    }
}
