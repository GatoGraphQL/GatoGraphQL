<?php

namespace PoP\FileStore;

use PoP\Root\App;
use PoP\Root\AbstractTestCase;

class ComponentTest extends AbstractTestCase
{
    /**
     * The component must have some dependency (only the root has not)
     */
    public function testHasDependedComponentClasses(): void
    {
        $this->assertNotEmpty(
            App::getComponent(Component::class)->getDependedComponentClasses()
        );
    }
}
