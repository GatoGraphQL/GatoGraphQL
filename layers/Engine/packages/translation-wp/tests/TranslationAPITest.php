<?php
declare(strict_types=1);
namespace PoP\TranslationWP;

use PoP\Translation\Facades\TranslationAPIFacade;

class TranslationAPITestCase extends \PHPUnit\Framework\TestCase
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
