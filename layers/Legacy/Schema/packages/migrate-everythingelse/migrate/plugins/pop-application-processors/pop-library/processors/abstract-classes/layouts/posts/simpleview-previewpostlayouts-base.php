<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_CustomSimpleViewPreviewPostLayoutsBase extends PoP_Module_Processor_BareSimpleViewPreviewPostLayoutsBase
{
    protected function getSimpleviewfeedBottomSubmodules(array $module)
    {
        $layouts = array();

        if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
            $layouts[] = [PoPCore_GenericForms_Module_Processor_SocialMediaPostWrappers::class, PoPCore_GenericForms_Module_Processor_SocialMediaPostWrappers::MODULE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER];
        }
        $layouts[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION];

        // Add the highlights and the referencedby. Lazy or not lazy?
        if (PoP_ApplicationProcessors_Utils::feedSimpleviewLazyload()) {
            $layouts[] = [PoP_Module_Processor_MultipleComponents::class, PoP_Module_Processor_MultipleComponents::MODULE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW];
        } else {
            $layouts[] = [PoP_Module_Processor_MultipleComponents::class, PoP_Module_Processor_MultipleComponents::MODULE_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW];
        }

        // Allow to override. Eg: TPP Debate website adds the Stance Counter
        $layouts = \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomPreviewPostLayoutsBase:simpleviewfeed_bottom_modules', $layouts, $module);

        return $layouts;
    }

    public function getAftercontentSubmodules(array $module)
    {
        $ret = parent::getAftercontentSubmodules($module);

        return array_merge(
            $ret,
            $this->getSimpleviewfeedBottomSubmodules($module)
        );
    }

    public function getTitleBeforeauthors(array $module, array &$props)
    {
        return array(
            'abovetitle' => sprintf(
                '<small class="visible-link inline">%s</small>',
                TranslationAPIFacade::getInstance()->__('link by', 'poptheme-wassup')
            )
        );
    }
}
