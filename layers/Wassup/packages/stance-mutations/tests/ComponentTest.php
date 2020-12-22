<?php

namespace PoPSitesWassup\StanceMutations;

use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{
    /**
     * The component must have some dependency (only the root has not)
     */
    public function testHasDependedComponentClasses(): void
    {
        $this->assertNotEmpty(
            Component::getDependedComponentClasses()
        );
    }
}
