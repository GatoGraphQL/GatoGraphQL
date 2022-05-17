<?php

namespace PoP\Root;

class ComponentTest extends AbstractTestCase
{
    /**
     * The root component cannot have any dependency
     */
    public function testHasNoDependencies(): void
    {
        $this->assertEmpty(
            App::getComponent(Module::class)->getDependedComponentClasses()
        );
        $this->assertEmpty(
            App::getComponent(Module::class)->getDependedConditionalComponentClasses()
        );
    }
}
