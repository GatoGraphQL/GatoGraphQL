<?php

class PoP_SocialNetwork_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public const MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT = 'forminputgroup-emailnotifications-network-createdpost';
    public const MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST = 'forminputgroup-emailnotifications-network-recommendedpost';
    public const MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER = 'forminputgroup-emailnotifications-network-followeduser';
    public const MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC = 'forminputgroup-emailnotifications-network-subscribedtotopic';
    public const MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT = 'forminputgroup-emailnotifications-network-addedcomment';
    public const MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST = 'forminputgroup-emailnotifications-network-updownvotedpost';
    public const MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT = 'forminputgroup-emailnotifications-subscribedtopic-createdcontent';
    public const MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT = 'forminputgroup-emailnotifications-subscribedtopic-addedcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT],
            [self::class, self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT],
            self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST],
            self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER],
            self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC],
            self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT],
            self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST],
            self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT],
            self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::MODULE_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT],
        );

        if ($component = $components[$module[1]]) {
            return $component;
        }
        
        return parent::getComponentSubmodule($module);
    }

    public function useComponentConfiguration(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT:
            case self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST:
            case self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER:
            case self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC:
            case self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT:
            case self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST:
            case self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT:
            case self::MODULE_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT:
                return false;
        }

        return parent::useComponentConfiguration($module);
    }
}



