<?php

declare(strict_types=1);

namespace PoP\TranslationWP;

use PoP\Root\Testing\PHPUnit\AbstractTestCase;
use PoP\Translation\Facades\TranslationAPIFacade;

class TranslationAPITest extends AbstractTestCase
{
    /**
     * Test that applyFilter returns $value
     */
    public function testTranslate(): void
    {
        /**
         * Currently WordPress is not set-up for testing
         * @todo Set-up WordPress for testing, then restore
         */
        return;
        
        $translationapi = TranslationAPIFacade::getInstance();
        $this->assertEquals(
            'There is no translation for this yet, sorry!',
            $translationapi->__('There is no translation for this yet, sorry!')
        );
    }
}
