<?php

namespace PoP\Root;

class ModuleTest extends AbstractTestCase
{
    /**
     * The root component cannot have any dependency
     */
    public function testHasNoDependencies(): void
    {
        $this->assertEmpty(
            App::getModule(Module::class)->getDependedModuleClasses()
        );
        $this->assertEmpty(
            App::getModule(Module::class)->getDependedConditionalComponentClasses()
        );
    }
}
