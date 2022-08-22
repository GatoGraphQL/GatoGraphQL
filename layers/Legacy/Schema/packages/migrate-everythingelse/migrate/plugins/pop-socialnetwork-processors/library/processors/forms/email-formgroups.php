<?php

class PoP_SocialNetwork_Module_Processor_EmailFormGroups extends PoP_Module_Processor_NoLabelFormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT = 'forminputgroup-emailnotifications-network-createdpost';
    public final const COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST = 'forminputgroup-emailnotifications-network-recommendedpost';
    public final const COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER = 'forminputgroup-emailnotifications-network-followeduser';
    public final const COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC = 'forminputgroup-emailnotifications-network-subscribedtotopic';
    public final const COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT = 'forminputgroup-emailnotifications-network-addedcomment';
    public final const COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST = 'forminputgroup-emailnotifications-network-updownvotedpost';
    public final const COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT = 'forminputgroup-emailnotifications-subscribedtopic-createdcontent';
    public final const COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT = 'forminputgroup-emailnotifications-subscribedtopic-addedcomment';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT,
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST,
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER,
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC,
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT,
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST,
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT,
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT,
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT],
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST],
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER],
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC],
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT],
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST],
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT],
            self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT => [PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::class, PoP_SocialNetwork_Module_Processor_UserProfileCheckboxFormInputs::COMPONENT_FORMINPUT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }

    public function useModuleConfiguration(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_CREATEDCONTENT:
            case self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_RECOMMENDEDPOST:
            case self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_FOLLOWEDUSER:
            case self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_SUBSCRIBEDTOTOPIC:
            case self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_ADDEDCOMMENT:
            case self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_NETWORK_UPDOWNVOTEDPOST:
            case self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_CREATEDCONTENT:
            case self::COMPONENT_FORMINPUTGROUP_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC_ADDEDCOMMENT:
                return false;
        }

        return parent::useModuleConfiguration($component);
    }
}



