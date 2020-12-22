<?php

declare(strict_types=1);

namespace PoP\Hooks;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractHookSet
{
    protected HooksAPIInterface $hooksAPI;
    protected TranslationAPIInterface $translationAPI;

    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI
    ) {
        $this->hooksAPI = $hooksAPI;
        $this->translationAPI = $translationAPI;

        // Initialize the hooks
        $this->init();
    }
    /**
     * Initialize the hooks
     *
     * @return void
     */
    abstract protected function init();
}
