<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddPostLinks_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_ADDPOSTLINKS_FORMINPUTGROUP_LINK = 'forminputgroup-postlink';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_ADDPOSTLINKS_FORMINPUTGROUP_LINK],
        );
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ADDPOSTLINKS_FORMINPUTGROUP_LINK:
                return [PoP_AddPostLinks_Module_Processor_TextFormInputs::class, PoP_AddPostLinks_Module_Processor_TextFormInputs::MODULE_ADDPOSTLINKS_FORMINPUT_LINK];
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function getInfo(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ADDPOSTLINKS_FORMINPUTGROUP_LINK:
                return TranslationAPIFacade::getInstance()->__('The URL from an external webpage, directly referenced by this post.', 'poptheme-wassup');
        }

        return parent::getInfo($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_ADDPOSTLINKS_FORMINPUTGROUP_LINK:
                $component = $this->getComponentSubmodule($componentVariation);
                $this->setProp($component, $props, 'placeholder', 'https://...');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



