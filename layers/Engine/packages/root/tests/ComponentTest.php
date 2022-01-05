<?php

namespace PoP\Root;

use PoP\Root\AbstractTestCase;
use PoP\Root\Managers\ComponentManager;

class ComponentTest extends AbstractTestCase
{
    /**
     * The root component cannot have any dependency
     */
    public function testHasNoDependencies(): void
    {
        $this->assertEmpty(
            \PoP\Root\App::getComponent(Component::class)->getDependedComponentClasses()
        );
        $this->assertEmpty(
            \PoP\Root\App::getComponent(Component::class)->getDependedConditionalComponentClasses()
        );
    }
}
