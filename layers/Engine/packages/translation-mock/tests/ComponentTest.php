<?php

declare(strict_types=1);

namespace PoP\TranslationMock;

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
    //         \PoP\Root\App::getComponentManager()->getComponent(Component::class)->getDependedComponentClasses()
    //     );
    // }
}
