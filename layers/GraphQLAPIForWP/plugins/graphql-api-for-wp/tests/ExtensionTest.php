<?php

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\PluginHelpers;
use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{
    public function testExtensionNamespace(): void
    {
        $this->assertEquals(
            PluginHelpers::getClassPSR4Namespace('GraphQLAPI\GraphQLAPI\Plugin'),
            'GraphQLAPI\GraphQLAPI'
        );
    }
}
