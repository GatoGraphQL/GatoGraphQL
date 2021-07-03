<?php

namespace PoP\Root;

use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{
    /**
     * The root component cannot have any dependency
     */
    public function testHasNoDependencies(): void
    {
        $this->assertEmpty(
            Component::getDependedComponentClasses()
        );
        $this->assertEmpty(
            Component::getDependedConditionalComponentClasses()
        );
    }
}
