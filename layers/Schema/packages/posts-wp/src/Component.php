<?php

declare(strict_types=1);

namespace PoPSchema\PostsWP;

use PoP\Engine\App;
use PoP\BasicService\Component\AbstractComponent;
use PoP\Root\Managers\ComponentManager;
use PoPSchema\Posts\Component as PostsComponent;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;

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
            \PoPSchema\Posts\Component::class,
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
        /** @var PostsComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(PostsComponent::class)->getConfiguration();
        if ($componentConfiguration->addPostTypeToCustomPostUnionTypes()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddPostTypeToCustomPostUnionTypes/Overrides');
        }
    }
}
