<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomiesWP;

use PoP\Root\Module\AbstractComponent;

/**
 * Initialize component
 */
class Module extends AbstractComponent
{
    /**
     * All component classes that this component satisfies
     *
     * @return string[]
     */
    public function getSatisfiedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\Taxonomies\Module::class,
        ];
    }

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\Taxonomies\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
    }
}
