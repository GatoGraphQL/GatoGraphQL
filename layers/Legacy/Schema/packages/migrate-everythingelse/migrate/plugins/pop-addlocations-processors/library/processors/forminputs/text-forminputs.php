<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_CreateLocationTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_EM_LOCATIONLAT = 'forminput-locationlatitude';
    public final const MODULE_FORMINPUT_EM_LOCATIONLNG = 'forminput-locationlongitude';
    public final const MODULE_FORMINPUT_EM_LOCATIONNAME = 'forminput-locationname';
    public final const MODULE_FORMINPUT_EM_LOCATIONADDRESS = 'forminput-locationaddress';
    public final const MODULE_FORMINPUT_EM_LOCATIONTOWN = 'forminput-locationtown';
    public final const MODULE_FORMINPUT_EM_LOCATIONSTATE = 'forminput-locationstate';
    public final const MODULE_FORMINPUT_EM_LOCATIONPOSTCODE = 'forminput-locationpostcode';
    public final const MODULE_FORMINPUT_EM_LOCATIONREGION = 'forminput-locationregion';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_EM_LOCATIONLAT],
            [self::class, self::COMPONENT_FORMINPUT_EM_LOCATIONLNG],
            [self::class, self::COMPONENT_FORMINPUT_EM_LOCATIONNAME],
            [self::class, self::COMPONENT_FORMINPUT_EM_LOCATIONADDRESS],
            [self::class, self::COMPONENT_FORMINPUT_EM_LOCATIONTOWN],
            [self::class, self::COMPONENT_FORMINPUT_EM_LOCATIONSTATE],
            [self::class, self::COMPONENT_FORMINPUT_EM_LOCATIONPOSTCODE],
            [self::class, self::COMPONENT_FORMINPUT_EM_LOCATIONREGION],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONNAME:
                return TranslationAPIFacade::getInstance()->__('Name', 'em-popprocessors');

            case self::COMPONENT_FORMINPUT_EM_LOCATIONADDRESS:
                return TranslationAPIFacade::getInstance()->__('Address', 'em-popprocessors');

            case self::COMPONENT_FORMINPUT_EM_LOCATIONTOWN:
                return TranslationAPIFacade::getInstance()->__('City', 'em-popprocessors');

            case self::COMPONENT_FORMINPUT_EM_LOCATIONSTATE:
                return TranslationAPIFacade::getInstance()->__('State', 'em-popprocessors');

            case self::COMPONENT_FORMINPUT_EM_LOCATIONPOSTCODE:
                return TranslationAPIFacade::getInstance()->__('Post code', 'em-popprocessors');

            case self::COMPONENT_FORMINPUT_EM_LOCATIONREGION:
                return TranslationAPIFacade::getInstance()->__('Region', 'em-popprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function isMandatory(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONNAME:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONTOWN:
                return true;
        }

        return parent::isMandatory($component, $props);
    }

    public function isHidden(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONLAT:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONLNG:
                return true;
        }

        return parent::isHidden($component, $props);
    }

    public function clearInput(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONNAME:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONLAT:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONLNG:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONADDRESS:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONTOWN:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONSTATE:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONPOSTCODE:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONREGION:
                return true;
        }

        return parent::clearInput($component, $props);
    }

    // function getName(array $component) {

    //     switch ($component[1]) {

    //          // Names needed by EM to create the Location
    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONLAT:

    //             return 'location_latitude';

    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONLNG:

    //             return 'location_longitude';

    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONNAME:

    //             return 'location_name';

    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONADDRESS:

    //             return 'location_address';

    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONTOWN:

    //             return 'location_town';

    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONSTATE:

    //             return 'location_state';

    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONPOSTCODE:

    //             return 'location_postcode';

    //         case self::COMPONENT_FORMINPUT_EM_LOCATIONREGION:

    //             return 'location_region';
    //     }

    //     return parent::getName($component);
    // }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_EM_LOCATIONLAT:
                $this->appendProp($component, $props, 'class', 'address-lat');
                break;

            case self::COMPONENT_FORMINPUT_EM_LOCATIONLNG:
                $this->appendProp($component, $props, 'class', 'address-lng');
                break;

            case self::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONADDRESS:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONTOWN:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONSTATE:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONPOSTCODE:
            case self::COMPONENT_FORMINPUT_EM_LOCATIONREGION:
                $this->appendProp($component, $props, 'class', 'address-input');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



