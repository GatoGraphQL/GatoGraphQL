<?php

class PoPVP_Module_Processor_CustomSectionSidebarInners extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTIPLE_SECTIONINNER_STANCES_SIDEBAR = 'multiple-sectioninner-stances-sidebar';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_MYSTANCES_SIDEBAR = 'multiple-sectioninner-mystances-sidebar';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_AUTHORSTANCES_SIDEBAR = 'multiple-sectioninner-authorstances-sidebar';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_STANCES_AUTHORROLE_SIDEBAR = 'multiple-sectioninner-stances-sidebar-authorrole';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_STANCES_STANCE_SIDEBAR = 'multiple-sectioninner-stances-sidebar-stance';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_AUTHORSTANCES_STANCE_SIDEBAR = 'multiple-sectioninner-authorstances-sidebar-stance';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_STANCES_GENERALSTANCE_SIDEBAR = 'multiple-sectioninner-stances-sidebar-generalstance';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_TAGSTANCES_SIDEBAR = 'multiple-sectioninner-tagstances-sidebar';
    public final const COMPONENT_MULTIPLE_SECTIONINNER_TAGSTANCES_STANCE_SIDEBAR = 'multiple-sectioninner-tagstances-sidebar-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_STANCES_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_MYSTANCES_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORSTANCES_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_STANCES_AUTHORROLE_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_STANCES_STANCE_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORSTANCES_STANCE_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_STANCES_GENERALSTANCE_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_TAGSTANCES_SIDEBAR],
            [self::class, self::COMPONENT_MULTIPLE_SECTIONINNER_TAGSTANCES_STANCE_SIDEBAR],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTIPLE_SECTIONINNER_STANCES_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_STANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_STANCES];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_MYSTANCES_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_MYSTANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_MYSTANCES];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORSTANCES_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_STANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_AUTHORSTANCES];
                break;
                
            case self::COMPONENT_MULTIPLE_SECTIONINNER_STANCES_AUTHORROLE_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_STANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_STANCES_AUTHORROLE];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_STANCES_STANCE_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_STANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_STANCES_STANCE];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORSTANCES_STANCE_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_STANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_AUTHORSTANCES_STANCE];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_STANCES_GENERALSTANCE_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_STANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_STANCES_GENERALSTANCE];
                break;
                    
            case self::COMPONENT_MULTIPLE_SECTIONINNER_TAGSTANCES_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_TAGSTANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_TAGSTANCES];
                break;

            case self::COMPONENT_MULTIPLE_SECTIONINNER_TAGSTANCES_STANCE_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_TAGSTANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_TAGSTANCES_STANCE];
                break;
                    
            case self::COMPONENT_MULTIPLE_AUTHORSINNERECTION_STANCES_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_AUTHORSTANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_AUTHORSTANCES];
                break;
                    
            case self::COMPONENT_MULTIPLE_AUTHORSINNERECTION_STANCES_STANCE_SIDEBAR:
                $ret[] = [PoPVP_Module_Processor_ButtonGroups::class, PoPVP_Module_Processor_ButtonGroups::COMPONENT_BUTTONGROUP_AUTHORSTANCES];
                $ret[] = [PoPVP_Module_Processor_CustomDelegatorFilters::class, PoPVP_Module_Processor_CustomDelegatorFilters::COMPONENT_DELEGATORFILTER_AUTHORSTANCES_STANCE];
                break;
        }
        
        return $ret;
    }
}



