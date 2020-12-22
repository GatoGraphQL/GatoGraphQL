<?php
declare(strict_types=1);

namespace PoP\HooksWP;

use PoP\Hooks\Facades\HooksAPIFacade;

class HooksAPITest extends \PHPUnit\Framework\TestCase
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
