<?php

declare(strict_types=1);

namespace PoP\HooksWP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Testing\PHPUnit\AbstractTestCase;

class HooksAPITest extends AbstractTestCase
{
    /**
     * Test that applyFilter returns $value
     */
    public function testApplyFilters(): void
    {
        /**
         * Currently WordPress is not set-up for testing
         * @todo Set-up WordPress for testing, then restore
         */
        return;

        $hooksapi = HooksAPIFacade::getInstance();
        $this->assertEquals(
            'bar',
            $hooksapi->applyFilters('foo', 'bar')
        );
    }
}
