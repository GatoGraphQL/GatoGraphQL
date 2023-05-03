<?php

declare(strict_types=1);

namespace PoP\Root;

class ModuleTest extends AbstractTestCase
{
    /**
     * The root module cannot have any dependency
     */
    public function testHasNoDependencies(): void
    {
        $this->assertEmpty(
            App::getModule(Module::class)->getDependedModuleClasses()
        );
        $this->assertEmpty(
            App::getModule(Module::class)->getDependedConditionalModuleClasses()
        );
    }
}
