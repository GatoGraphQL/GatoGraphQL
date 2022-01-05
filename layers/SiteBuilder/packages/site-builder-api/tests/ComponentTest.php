<?php

namespace PoP\SiteBuilderAPI;

use PHPUnit\Framework\TestCase;

/**
 * Made abstract to disable the test
 */
abstract class ComponentTest extends TestCase
{
    // /**
    //  * The component must have some dependency (only the root has not)
    //  */
    // public function testHasDependedComponentClasses(): void
    // {
    //     $this->assertNotEmpty(
    //         \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getDependedComponentClasses()
    //     );
    // }
}
