<?php

declare(strict_types=1);

namespace PoP\Root\Helpers;

use PHPUnit\Framework\TestCase;

class ClassHelpersTest extends TestCase
{
    public function testClassPSR4Namespace(): void
    {
        $this->assertEquals(
            ClassHelpers::getClassPSR4Namespace('GatoGraphQL\GatoGraphQL\Plugin'),
            'GatoGraphQL\GatoGraphQL'
        );
    }
}
