<?php

declare(strict_types=1);

namespace PoPCMSSchema\PagesWP;

use PoP\Root\App;
use PoP\Root\Component\AbstractComponent;
use PoPCMSSchema\Pages\ComponentConfiguration as PagesComponentConfiguration;
use PoPCMSSchema\Pages\Component as PagesComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * All component classes that this component satisfies
     *
     * @return string[]
     */
    public function getSatisfiedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\Pages\Component::class,
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
            \PoPCMSSchema\Pages\Component::class,
            \PoPCMSSchema\CustomPostsWP\Component::class,
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
        /** @var PagesComponentConfiguration */
        $componentConfiguration = App::getComponent(PagesComponent::class)->getConfiguration();
        if ($componentConfiguration->addPageTypeToCustomPostUnionTypes()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddPageTypeToCustomPostUnionTypes/Overrides');
        }
    }
}
