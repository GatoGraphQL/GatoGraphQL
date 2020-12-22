<?php

class PoPVP_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public const MODULE_DELEGATORFILTER_STANCES = 'delegatorfilter-stances';
    public const MODULE_DELEGATORFILTER_AUTHORSTANCES = 'delegatorfilter-authorstance';
    public const MODULE_DELEGATORFILTER_MYSTANCES = 'delegatorfilter-mystances';
    public const MODULE_DELEGATORFILTER_STANCES_AUTHORROLE = 'delegatorfilter-stances-authorrole';
    public const MODULE_DELEGATORFILTER_STANCES_STANCE = 'delegatorfilter-stances-stance';
    public const MODULE_DELEGATORFILTER_AUTHORSTANCES_STANCE = 'delegatorfilter-authorstances-stance';
    public const MODULE_DELEGATORFILTER_STANCES_GENERALSTANCE = 'delegatorfilter-stances-generalstance';
    public const MODULE_DELEGATORFILTER_TAGSTANCES = 'delegatorfilter-tagstances';
    public const MODULE_DELEGATORFILTER_TAGSTANCES_STANCE = 'delegatorfilter-tagstances-stance';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_STANCES],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORSTANCES],
            [self::class, self::MODULE_DELEGATORFILTER_MYSTANCES],
            [self::class, self::MODULE_DELEGATORFILTER_STANCES_AUTHORROLE],
            [self::class, self::MODULE_DELEGATORFILTER_STANCES_STANCE],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORSTANCES_STANCE],
            [self::class, self::MODULE_DELEGATORFILTER_STANCES_GENERALSTANCE],
            [self::class, self::MODULE_DELEGATORFILTER_TAGSTANCES],
            [self::class, self::MODULE_DELEGATORFILTER_TAGSTANCES_STANCE],
        );
    }
    
    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_STANCES => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_STANCES],
            self::MODULE_DELEGATORFILTER_AUTHORSTANCES => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORSTANCES],
            self::MODULE_DELEGATORFILTER_MYSTANCES => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_MYSTANCES],
            self::MODULE_DELEGATORFILTER_STANCES_AUTHORROLE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_STANCES_AUTHORROLE],
            self::MODULE_DELEGATORFILTER_STANCES_STANCE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_STANCES_STANCE],
            self::MODULE_DELEGATORFILTER_AUTHORSTANCES_STANCE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORSTANCES_STANCE],
            self::MODULE_DELEGATORFILTER_STANCES_GENERALSTANCE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_STANCES_GENERALSTANCE],
            self::MODULE_DELEGATORFILTER_TAGSTANCES => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_STANCES],
            self::MODULE_DELEGATORFILTER_TAGSTANCES_STANCE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_STANCES_STANCE],
        );

        if ($inner = $inners[$module[1]]) {
            return $inner;
        }
    
        return parent::getInnerSubmodule($module);
    }
}



