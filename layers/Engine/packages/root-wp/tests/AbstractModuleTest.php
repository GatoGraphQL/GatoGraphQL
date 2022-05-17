<?php

namespace PoP\RootWP;

use PoP\Root\App;

abstract class AbstractModuleTest extends AbstractTestCase
{
    /**
     * The module must have some dependency (only the root has not)
     */
    public function testHasDependedModuleClasses(): void
    {
        $this->assertNotEmpty(
            App::getModule($this->getModuleClass())->getDependedModuleClasses()
        );
    }
}
