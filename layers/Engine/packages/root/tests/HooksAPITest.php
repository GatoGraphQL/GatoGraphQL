<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\AbstractTestCaseCase;

class HooksAPITest extends AbstractTestCaseCase
{
    /**
     * Test that applyFilter returns $value
     */
    public function testApplyFilters(): void
    {
        $hooksapi = App::getHookManager();
        $this->assertEquals(
            'bar',
            $hooksapi->applyFilters('foo', 'bar')
        );
    }
}
