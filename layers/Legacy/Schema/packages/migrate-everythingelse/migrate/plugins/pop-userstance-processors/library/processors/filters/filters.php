<?php

class UserStance_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const COMPONENT_FILTER_STANCES = 'filter-stances';
    public final const COMPONENT_FILTER_AUTHORSTANCES = 'filter-authorstance';
    public final const COMPONENT_FILTER_MYSTANCES = 'filter-mystances';
    public final const COMPONENT_FILTER_STANCES_AUTHORROLE = 'filter-stances-authorrole';
    public final const COMPONENT_FILTER_STANCES_STANCE = 'filter-stances-stance';
    public final const COMPONENT_FILTER_AUTHORSTANCES_STANCE = 'filter-authorstances-stance';
    public final const COMPONENT_FILTER_STANCES_GENERALSTANCE = 'filter-stances-generalstance';
    public final const COMPONENT_FILTER_TAGSTANCES = 'filter-tagstances';
    public final const COMPONENT_FILTER_TAGSTANCES_STANCE = 'filter-tagstances-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTER_STANCES],
            [self::class, self::COMPONENT_FILTER_AUTHORSTANCES],
            [self::class, self::COMPONENT_FILTER_MYSTANCES],
            [self::class, self::COMPONENT_FILTER_STANCES_AUTHORROLE],
            [self::class, self::COMPONENT_FILTER_STANCES_STANCE],
            [self::class, self::COMPONENT_FILTER_AUTHORSTANCES_STANCE],
            [self::class, self::COMPONENT_FILTER_STANCES_GENERALSTANCE],
            [self::class, self::COMPONENT_FILTER_TAGSTANCES],
            [self::class, self::COMPONENT_FILTER_TAGSTANCES_STANCE],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_STANCES => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_STANCES],
            self::COMPONENT_FILTER_AUTHORSTANCES => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORSTANCES],
            self::COMPONENT_FILTER_MYSTANCES => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYSTANCES],
            self::COMPONENT_FILTER_STANCES_AUTHORROLE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_STANCES_AUTHORROLE],
            self::COMPONENT_FILTER_STANCES_STANCE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_STANCES_STANCE],
            self::COMPONENT_FILTER_AUTHORSTANCES_STANCE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORSTANCES_STANCE],
            self::COMPONENT_FILTER_STANCES_GENERALSTANCE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_STANCES_GENERALSTANCE],
            self::COMPONENT_FILTER_TAGSTANCES => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_STANCES],
            self::COMPONENT_FILTER_TAGSTANCES_STANCE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_STANCES_STANCE],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


