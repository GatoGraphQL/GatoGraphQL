<?php

namespace GraphQLAPI\GraphQLAPI;

use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{
    /**
     * The component must have some dependency (only the root has not)
     */
    public function testExtensionNamespace(): void
    {
        $extension = new Extension('');
        $this->assertEquals(
            $extension->getPluginNamespace(),
            'GraphQLAPI\GraphQLAPI'
        );
    }
}
