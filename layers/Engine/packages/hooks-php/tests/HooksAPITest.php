<?php

declare(strict_types=1);

namespace PoP\HooksPHP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\AbstractTestCase;

class HooksAPITest extends AbstractTestCase
{
    /**
     * Test that applyFilter returns $value
     */
    public function testApplyFilters(): void
    {
        $hooksapi = HooksAPIFacade::getInstance();
        $this->assertEquals(
            'bar',
            $hooksapi->applyFilters('foo', 'bar')
        );
    }
}
