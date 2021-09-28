<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\CodeQuality\Configurators;

use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;

class CodeQualityContainerConfigurationService extends AbstractCodeQualityContainerConfigurationService
{
    public function configureContainer(): void
    {
        parent::configureContainer();
        
        $services = $this->containerConfigurator->services();
        $services->set(RemoveUselessParamTagRector::class);
        $services->set(RemoveUselessReturnTagRector::class);
        // $services->set(DowngradePropertyPromotionRector::class);
    }
}
