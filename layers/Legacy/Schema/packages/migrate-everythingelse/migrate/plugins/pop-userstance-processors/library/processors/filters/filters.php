<?php

class UserStance_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const MODULE_FILTER_STANCES = 'filter-stances';
    public final const MODULE_FILTER_AUTHORSTANCES = 'filter-authorstance';
    public final const MODULE_FILTER_MYSTANCES = 'filter-mystances';
    public final const MODULE_FILTER_STANCES_AUTHORROLE = 'filter-stances-authorrole';
    public final const MODULE_FILTER_STANCES_STANCE = 'filter-stances-stance';
    public final const MODULE_FILTER_AUTHORSTANCES_STANCE = 'filter-authorstances-stance';
    public final const MODULE_FILTER_STANCES_GENERALSTANCE = 'filter-stances-generalstance';
    public final const MODULE_FILTER_TAGSTANCES = 'filter-tagstances';
    public final const MODULE_FILTER_TAGSTANCES_STANCE = 'filter-tagstances-stance';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_STANCES],
            [self::class, self::MODULE_FILTER_AUTHORSTANCES],
            [self::class, self::MODULE_FILTER_MYSTANCES],
            [self::class, self::MODULE_FILTER_STANCES_AUTHORROLE],
            [self::class, self::MODULE_FILTER_STANCES_STANCE],
            [self::class, self::MODULE_FILTER_AUTHORSTANCES_STANCE],
            [self::class, self::MODULE_FILTER_STANCES_GENERALSTANCE],
            [self::class, self::MODULE_FILTER_TAGSTANCES],
            [self::class, self::MODULE_FILTER_TAGSTANCES_STANCE],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_FILTER_STANCES => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_STANCES],
            self::MODULE_FILTER_AUTHORSTANCES => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORSTANCES],
            self::MODULE_FILTER_MYSTANCES => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYSTANCES],
            self::MODULE_FILTER_STANCES_AUTHORROLE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_STANCES_AUTHORROLE],
            self::MODULE_FILTER_STANCES_STANCE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_STANCES_STANCE],
            self::MODULE_FILTER_AUTHORSTANCES_STANCE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORSTANCES_STANCE],
            self::MODULE_FILTER_STANCES_GENERALSTANCE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_STANCES_GENERALSTANCE],
            self::MODULE_FILTER_TAGSTANCES => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_STANCES],
            self::MODULE_FILTER_TAGSTANCES_STANCE => [UserStance_Module_Processor_CustomFilterInners::class, UserStance_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_STANCES_STANCE],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }
}


