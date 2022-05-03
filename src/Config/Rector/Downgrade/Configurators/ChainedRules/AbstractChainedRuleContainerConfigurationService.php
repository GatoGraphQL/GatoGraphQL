<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use PoP\PoP\Config\Rector\Configurators\AbstractContainerConfigurationService;
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
        foreach ($this->getRectorRuleClasses() as $rectorRuleClass) {
            $this->rectorConfig->rule($rectorRuleClass);
        }

        $this->rectorConfig->phpVersion(PhpVersion::PHP_71);
        $this->rectorConfig->disableImportNames();
        $this->rectorConfig->disableImportShortClasses();

        $this->rectorConfig->paths($this->getPaths());
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
