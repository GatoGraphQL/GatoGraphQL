<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\AbstractTestCase;

class HooksAPITest extends AbstractTestCase
{
    /**
     * Test that applyFilter returns $value
     */
    public function testApplyFilters(): void
    {
        $hooksapi = \PoP\Root\App::getHookManager();
        $this->assertEquals(
            'bar',
            $hooksapi->applyFilters('foo', 'bar')
        );
    }
}
