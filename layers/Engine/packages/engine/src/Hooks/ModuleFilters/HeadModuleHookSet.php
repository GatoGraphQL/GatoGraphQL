<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\ModuleFilters;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\ModuleFilters\HeadModule;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

class HeadModuleHookSet extends AbstractHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        protected HeadModule $headModule
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI
        );
    }

    protected function init()
    {
        $this->hooksAPI->addFilter(
            ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
            [$this, 'maybeAddComponent']
        );
        $this->hooksAPI->addAction(
            'ApplicationState:addVars',
            [$this, 'addVars'],
            10,
            1
        );
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        if (isset($vars['modulefilter']) && $vars['modulefilter'] == $this->headModule->getName()) {
            if ($headmodule = $_REQUEST[HeadModule::URLPARAM_HEADMODULE] ?? null) {
                $vars['headmodule'] = ModuleUtils::getModuleFromOutputName($headmodule);
            }
        }
    }
    public function maybeAddComponent($components)
    {
        $vars = ApplicationState::getVars();
        if (isset($vars['modulefilter']) && $vars['modulefilter'] == $this->headModule->getName()) {
            if ($headmodule = $vars['headmodule']) {
                $components[] = $this->translationAPI->__('head module:', 'engine') . ModuleUtils::getModuleFullName($headmodule);
            }
        }

        return $components;
    }
}
