<?php

namespace PoP\MandatoryDirectivesByConfiguration;

use PoP\Root\AbstractTestCase;
use PoP\Root\Managers\ComponentManager;

class ComponentTest extends AbstractTestCase
{
    /**
     * The component must have some dependency (only the root has not)
     */
    public function testHasDependedComponentClasses(): void
    {
        $this->assertNotEmpty(
            \PoP\Root\App::getComponent(Component::class)->getDependedComponentClasses()
        );
    }
}
