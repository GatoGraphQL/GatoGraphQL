<?php

namespace PoP\RootWP;

use PoP\Root\App;

abstract class AbstractModuleTest extends AbstractTestCase
{
    /**
     * The component must have some dependency (only the root has not)
     */
    public function testHasDependedComponentClasses(): void
    {
        $this->assertNotEmpty(
            App::getComponent($this->getComponentClass())->getDependedComponentClasses()
        );
    }
}
