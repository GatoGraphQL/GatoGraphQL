<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\Misc;

use PoP\Engine\ModuleFilters\HeadModule;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

class URLHookSet extends AbstractHookSet
{
    protected HeadModule $headModule;

    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        HeadModule $headModule
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI
        );
        $this->headModule = $headModule;
    }

    protected function init()
    {
        $this->hooksAPI->addFilter(
            'RequestUtils:current_url:remove_params',
            [$this, 'getParamsToRemoveFromURL']
        );
    }
    public function getParamsToRemoveFromURL($params)
    {
        $params[] = $this->headModule::URLPARAM_HEADMODULE;
        return $params;
    }
}
