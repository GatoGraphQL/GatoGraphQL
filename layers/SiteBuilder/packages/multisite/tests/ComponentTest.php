<?php

namespace PoP\Multisite;

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
    //         \PoP\Root\App::getComponent(Component::class)->getDependedComponentClasses()
    //     );
    // }
}
