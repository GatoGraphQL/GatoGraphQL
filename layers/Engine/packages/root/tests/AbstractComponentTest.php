<?php

namespace PoP\Root;

abstract class AbstractComponentTest extends AbstractTestCase
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
