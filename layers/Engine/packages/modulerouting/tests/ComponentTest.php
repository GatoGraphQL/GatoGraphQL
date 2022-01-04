<?php

namespace PoP\ModuleRouting;

use PoP\Root\AbstractTestCase;

/**
 * Made abstract to disable the test
 */
abstract class ComponentTest extends AbstractTestCase
{
    // /**
    //  * The component must have some dependency (only the root has not)
    //  */
    // public function testHasDependedComponentClasses(): void
    // {
    //     $this->assertNotEmpty(
    //         ComponentManager::getComponent(Component::class)->getDependedComponentClasses()
    //     );
    // }
}
