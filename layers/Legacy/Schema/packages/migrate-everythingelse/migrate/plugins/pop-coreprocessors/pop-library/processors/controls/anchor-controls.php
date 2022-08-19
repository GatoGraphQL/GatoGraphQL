<?php
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_ANCHORCONTROL_TOGGLEOPTIONALFIELDS = 'anchorcontrol-toggleoptionalfields';
    public final const COMPONENT_ANCHORCONTROL_EXPANDCOLLAPSIBLE = 'anchorcontrol-expandcollapsible';
    public final const COMPONENT_ANCHORCONTROL_FILTERTOGGLE = 'anchorcontrol-filtertoggle';
    public final const COMPONENT_ANCHORCONTROL_CURRENTURL = 'anchorcontrol-currenturl';
    public final const COMPONENT_ANCHORCONTROL_SUBMENUTOGGLE_XS = 'anchorcontrol-submenutoggle-xs';
    public final const COMPONENT_ANCHORCONTROL_PRINT = 'anchorcontrol-print';
    public final const COMPONENT_ANCHORCONTROL_CLOSEPAGE = 'anchorcontrol-closepage';
    public final const COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN = 'anchorcontrol-closepagebtn';
    public final const COMPONENT_ANCHORCONTROL_CLOSEPAGEBTNBIG = 'anchorcontrol-closepagebtnbig';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_ANCHORCONTROL_TOGGLEOPTIONALFIELDS,
            self::COMPONENT_ANCHORCONTROL_EXPANDCOLLAPSIBLE,
            self::COMPONENT_ANCHORCONTROL_FILTERTOGGLE,
            self::COMPONENT_ANCHORCONTROL_CURRENTURL,
            self::COMPONENT_ANCHORCONTROL_SUBMENUTOGGLE_XS,
            self::COMPONENT_ANCHORCONTROL_PRINT,
            self::COMPONENT_ANCHORCONTROL_CLOSEPAGE,
            self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN,
            self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTNBIG,
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
                return TranslationAPIFacade::getInstance()->__('Toggle optional fields', 'pop-coreprocessors');

            case self::COMPONENT_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
                return TranslationAPIFacade::getInstance()->__('Expand', 'pop-coreprocessors');

            case self::COMPONENT_ANCHORCONTROL_FILTERTOGGLE:
                return TranslationAPIFacade::getInstance()->__('Filter', 'pop-coreprocessors');

            case self::COMPONENT_ANCHORCONTROL_CURRENTURL:
                return TranslationAPIFacade::getInstance()->__('Permalink', 'pop-coreprocessors');

            case self::COMPONENT_ANCHORCONTROL_PRINT:
                return TranslationAPIFacade::getInstance()->__('Print', 'pop-coreprocessors');

            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGE:
            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN:
            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTNBIG:
                return TranslationAPIFacade::getInstance()->__('Close', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_FILTERTOGGLE:
            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN:
                return null;
        }

        return parent::getText($component, $props);
    }
    public function getIcon(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_SUBMENUTOGGLE_XS:
                return 'glyphicon-menu-hamburger';

            case self::COMPONENT_ANCHORCONTROL_PRINT:
                return 'glyphicon-print';

            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGE:
            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN:
            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTNBIG:
                return 'glyphicon-remove';
        }

        return parent::getIcon($component);
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
                return 'fa-star-half-o';

            case self::COMPONENT_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
                return 'fa-arrows-v';

            case self::COMPONENT_ANCHORCONTROL_FILTERTOGGLE:
                return 'fa-filter';

            case self::COMPONENT_ANCHORCONTROL_CURRENTURL:
                return 'fa-link';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
            case self::COMPONENT_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
                // $block_id = $props['block-id'];
                // return '#'.$block_id.' .collapse';
                return $this->getProp($component, $props, 'target');

            case self::COMPONENT_ANCHORCONTROL_FILTERTOGGLE:
                // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
                if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                     // Comment Leo: calling ->getFrontendId( will possibly not work inside of a replicated pageSection, since this ID is hardcoded in the settings
                    // however the ID of the target will change. To deal with this, possibly use a jQuery selector other than $('#').
                    // Currently this is not a problem because we don't have any pageSection to be replicated that needs a filter (nothing listing content, only to create, eg: Add Project)

                    // Comment Leo 26/03/2019: This must be re-implemented through Handlebars function `upcomingModuleId`
                    // // The Filter is set in the props
                    // if ($filter = $this->getProp($component, $props, 'filter-component')) {
                    //     $filter_id = $componentprocessor_manager->getComponentProcessor($filter)->getFrontendId($filter, $props);
                    //     return '#'.$filter_id;
                    // }
                    return '#'; // This must be replaced! Not working now!
                }
                return null;

            case self::COMPONENT_ANCHORCONTROL_CURRENTURL:
                $requestHelperService = RequestHelperServiceFacade::getInstance();
                return $requestHelperService->getCurrentURL();

            case self::COMPONENT_ANCHORCONTROL_SUBMENUTOGGLE_XS:
                // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
                if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
                     // The Submenu target is set in the props by the block
                    return $this->getProp($component, $props, 'submenu-target');
                }
                return null;
        }

        return parent::getHref($component, $props);
    }

    public function getTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_CURRENTURL:
                return \PoP\ConfigurationComponentModel\Constants\Targets::MAIN;
        }

        return parent::getTarget($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
                $this->appendProp($component, $props, 'class', 'btn btn-primary');
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'collapse'
                    )
                );
                break;

            case self::COMPONENT_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
            case self::COMPONENT_ANCHORCONTROL_FILTERTOGGLE:
            case self::COMPONENT_ANCHORCONTROL_SUBMENUTOGGLE_XS:
                $this->appendProp($component, $props, 'class', 'btn btn-compact btn-link');
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'collapse'
                    )
                );
                break;

            case self::COMPONENT_ANCHORCONTROL_PRINT:
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-blocktarget' => $this->getProp($component, $props, 'control-target')
                    )
                );
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_CURRENTURL:
                $this->appendProp($component, $props, 'class', 'btn btn-compact btn-link');
                break;

            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTNBIG:
                $this->appendProp($component, $props, 'class', 'close');
                break;

            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN:
                $this->appendProp($component, $props, 'class', 'btn btn-link');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_ANCHORCONTROL_PRINT:
                $this->addJsmethod($ret, 'controlPrint');
                break;

            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGE:
            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTN:
            case self::COMPONENT_ANCHORCONTROL_CLOSEPAGEBTNBIG:
                $this->addJsmethod($ret, 'closePageTab');
                break;
        }
        return $ret;
    }
}


