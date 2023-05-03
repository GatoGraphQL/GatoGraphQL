<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\App;

abstract class AbstractModuleTestCase extends AbstractTestCaseCase
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
