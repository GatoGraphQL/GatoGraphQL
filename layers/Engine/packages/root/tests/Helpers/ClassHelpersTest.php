<?php

namespace PoP\Root\Helpers;

use PHPUnit\Framework\TestCase;
use PoP\Root\Helpers\ClassHelpers;

class ClassHelpersTest extends TestCase
{
    public function testClassPSR4Namespace(): void
    {
        $this->assertEquals(
            ClassHelpers::getClassPSR4Namespace('GraphQLAPI\GraphQLAPI\Plugin'),
            'GraphQLAPI\GraphQLAPI'
        );
    }
}
