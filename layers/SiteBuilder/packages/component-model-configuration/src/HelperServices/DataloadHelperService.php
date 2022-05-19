<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\HelperServices;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ComponentProcessors\FilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\Root\Services\BasicServiceTrait;

class DataloadHelperService implements DataloadHelperServiceInterface
{
    use BasicServiceTrait;

    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;

    final public function setComponentProcessorManager(ComponentProcessorManagerInterface $componentProcessorManager): void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        return $this->componentProcessorManager ??= $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }

    /**
     * @param array<array<string, mixed>> $componentValues
     */
    public function addFilterParams(string $url, array $componentValues = []): string
    {
        $args = [];
        foreach ($componentValues as $componentValue) {
            $component = $componentValue['component'];
            $value = $componentValue['value'];
            /** @var FilterInputComponentProcessorInterface */
            $componentProcessor = $this->getComponentProcessorManager()->getProcessor($component);
            $args[$componentProcessor->getName($component)] = $value;
        }
        return GeneralUtils::addQueryArgs($args, $url);
    }
}
