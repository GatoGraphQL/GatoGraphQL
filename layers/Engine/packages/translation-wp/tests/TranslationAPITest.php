<?php

declare(strict_types=1);

namespace PoP\TranslationWP;

use PHPUnit\Framework\TestCase;
use PoP\Translation\Facades\TranslationAPIFacade;

class TranslationAPITest extends TestCase
{
    /**
     * Test that applyFilter returns $value
     */
    public function testTranslate(): void
    {
        $translationapi = TranslationAPIFacade::getInstance();
        $this->assertEquals(
            'There is no translation for this yet, sorry!',
            $translationapi->__('There is no translation for this yet, sorry!')
        );
    }
}
