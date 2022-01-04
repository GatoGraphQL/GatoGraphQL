<?php

declare(strict_types=1);

namespace PoP\TranslationWP;

use PoP\Engine\AbstractTestCase;

class ComponentTest extends AbstractTestCase
{
    /**
     * The component must have some dependency (only the root has not)
     */
    public function testHasDependedComponentClasses(): void
    {
        $this->assertNotEmpty(
            \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getDependedComponentClasses()
        );
    }
}
