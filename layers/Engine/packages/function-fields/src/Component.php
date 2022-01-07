<?php

declare(strict_types=1);

namespace PoP\FunctionFields;

use PoP\BasicService\Component\AbstractComponent;
use PoP\Root\App;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoP\ComponentModel\Component::class,
        ];
    }

    public function isEnabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return !$componentConfiguration->disableFunctionFields();
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    }
}
