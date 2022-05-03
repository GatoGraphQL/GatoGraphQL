<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use PoP\PoP\Config\Rector\Configurators\AbstractContainerConfigurationService;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;

/**
 * Hack to fix bug.
 *
 * Chained rules in Rector may not be applied, so a second rule
 * must be manually triggered to complete the downgrade.
 *
 * @see https://github.com/rectorphp/rector/issues/5962
 */
abstract class AbstractChainedRuleContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function configureContainer(): void
    {
        // get parameters
        $parameters = $this->rectorConfig->parameters();

        $services = $this->rectorConfig->services();
        foreach ($this->getRectorRuleClasses() as $rectorRuleClass) {
            $services->set($rectorRuleClass);
        }

        $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);
        $parameters->set(Option::AUTO_IMPORT_NAMES, false);
        $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

        $parameters->set(Option::PATHS, $this->getPaths());
    }

    /**
     * @return string[]
     */
    abstract protected function getRectorRuleClasses(): array;

    /**
     * @return string[]
     */
    abstract protected function getPaths(): array;
}
