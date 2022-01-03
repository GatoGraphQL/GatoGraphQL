<?php

declare(strict_types=1);

namespace PoP\HooksPHP;

use PHPUnit\Framework\TestCase;
use PoP\Hooks\Facades\HooksAPIFacade;

class HooksAPITest extends TestCase
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
