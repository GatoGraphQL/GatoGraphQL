<?php

declare(strict_types=1);

namespace PoPSchema\PagesWP;

use PoP\BasicService\Component\AbstractComponent;
use PoP\Root\Managers\ComponentManager;
use PoPSchema\Pages\ComponentConfiguration as PagesComponentConfiguration;
use PoPSchema\Pages\Component as PagesComponent;

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
            \PoPSchema\Pages\Component::class,
            \PoPSchema\CustomPostsWP\Component::class,
        ];
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
        $this->initServices(dirname(__DIR__));
        /** @var PagesComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(PagesComponent::class)->getConfiguration();
        if ($componentConfiguration->addPageTypeToCustomPostUnionTypes()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddPageTypeToCustomPostUnionTypes/Overrides');
        }
    }
}
