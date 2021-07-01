<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

class ExtensionDemoContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function __construct(
        protected ContainerConfigurator $containerConfigurator,
        protected string $rootDirectory,
    ) {
    }
    
    public function applyCustomConfiguration(): void
    {
        // get parameters
        $parameters = $this->containerConfigurator->parameters();

        // files to skip downgrading
        $parameters->set(Option::SKIP, [
            // Skip tests
            '*/tests/*',
            '*/test/*',
            '*/Test/*',
        ]);
    }
}
