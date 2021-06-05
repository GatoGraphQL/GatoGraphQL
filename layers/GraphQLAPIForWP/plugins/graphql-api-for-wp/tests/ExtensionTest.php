<?php

namespace GraphQLAPI\GraphQLAPI;

use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{
    public function testExtensionNamespace(): void
    {
        $extension = new Extension('', '');
        $this->assertEquals(
            $extension->getPluginNamespace(),
            'GraphQLAPI\GraphQLAPI'
        );
    }
}
