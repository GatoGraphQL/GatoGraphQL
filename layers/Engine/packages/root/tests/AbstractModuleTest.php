<?php

namespace PoP\Root;

abstract class AbstractModuleTest extends AbstractTestCase
{
    /**
     * The module must have some dependency (only the root has not)
     */
    public function testHasDependedComponentClasses(): void
    {
        $this->assertNotEmpty(
            App::getModule($this->getModuleClass())->getDependedModuleClasses()
        );
    }
}
