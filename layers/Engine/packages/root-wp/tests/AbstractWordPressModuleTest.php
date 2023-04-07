<?php

declare(strict_types=1);

namespace PoP\RootWP;

use PoP\Root\App;

abstract class AbstractWordPressModuleTest extends AbstractWordPressTestCase
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
