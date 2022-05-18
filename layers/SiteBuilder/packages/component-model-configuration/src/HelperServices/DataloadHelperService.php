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
     * @param array<array<string, mixed>> $componentVariationValues
     */
    public function addFilterParams(string $url, array $componentVariationValues = []): string
    {
        $args = [];
        foreach ($componentVariationValues as $componentVariationValue) {
            $componentVariation = $componentVariationValue['component-variation'];
            $value = $componentVariationValue['value'];
            /** @var FilterInputComponentProcessorInterface */
            $componentProcessor = $this->getComponentProcessorManager()->getProcessor($componentVariation);
            $args[$componentProcessor->getName($componentVariation)] = $value;
        }
        return GeneralUtils::addQueryArgs($args, $url);
    }
}
