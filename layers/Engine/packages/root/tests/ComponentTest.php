<?php

namespace PoP\Root;

use PoP\Engine\AbstractTestCase;

class ComponentTest extends AbstractTestCase
{
    /**
     * The root component cannot have any dependency
     */
    public function testHasNoDependencies(): void
    {
        $this->assertEmpty(
            \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getDependedComponentClasses()
        );
        $this->assertEmpty(
            \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getDependedConditionalComponentClasses()
        );
    }
}
