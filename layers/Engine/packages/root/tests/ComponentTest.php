<?php

namespace PoP\Root;

use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
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
