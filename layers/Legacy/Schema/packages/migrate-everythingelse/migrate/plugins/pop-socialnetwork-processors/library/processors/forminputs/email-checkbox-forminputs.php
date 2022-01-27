<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs extends PoP_UserPlatform_Module_Processor_UserPreferencesCheckboxFormInputs
{
    public const MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT = 'forminput-emailnotifications-network-createdpost';
    public const MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST = 'forminput-emailnotifications-network-recommendedpost';
    public const MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER = 'forminput-emailnotifications-network-followeduser';
    public const MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC = 'forminput-emailnotifications-network-subscribedtotopic';
    public const MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT = 'forminput-emailnotifications-network-addedcomment';
    public const MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST = 'forminput-emailnotifications-network-updownvotedpost';
    public const MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT = 'forminput-emailnotifications-subscribedtopic-createdcontent';
    public const MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT = 'forminput-emailnotifications-subscribedtopic-addedcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT],
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST],
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER],
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC],
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT],
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST],
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT],
            [self::class, self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT:
                return TranslationAPIFacade::getInstance()->__('Created content', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST:
                return TranslationAPIFacade::getInstance()->__('Recommends content', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER:
                return TranslationAPIFacade::getInstance()->__('Follows another user', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC:
                return TranslationAPIFacade::getInstance()->__('Subscribed to a topic', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT:
                return TranslationAPIFacade::getInstance()->__('Added a comment', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST:
                return TranslationAPIFacade::getInstance()->__('Up/down-voted a highlight', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT:
                return TranslationAPIFacade::getInstance()->__('Has new content', 'pop-coreprocessors');
            
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT:
                return TranslationAPIFacade::getInstance()->__('Has a comment added', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getCheckboxValue(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT:
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST:
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER:
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC:
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT:
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST:
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT:
            case self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT:
                $values = array(
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT => POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT,
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST => POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST,
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER => POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER,
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC => POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC,
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT => POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT,
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST => POP_USERPREFERENCES_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST,
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT => POP_USERPREFERENCES_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT,
                    self::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT => POP_USERPREFERENCES_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT,
                );
                return $values[$module[1]];
        }

        return parent::getCheckboxValue($module, $props);
    }
}



