<?php

class PoPVP_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const COMPONENT_DELEGATORFILTER_STANCES = 'delegatorfilter-stances';
    public final const COMPONENT_DELEGATORFILTER_AUTHORSTANCES = 'delegatorfilter-authorstance';
    public final const COMPONENT_DELEGATORFILTER_MYSTANCES = 'delegatorfilter-mystances';
    public final const COMPONENT_DELEGATORFILTER_STANCES_AUTHORROLE = 'delegatorfilter-stances-authorrole';
    public final const COMPONENT_DELEGATORFILTER_STANCES_STANCE = 'delegatorfilter-stances-stance';
    public final const COMPONENT_DELEGATORFILTER_AUTHORSTANCES_STANCE = 'delegatorfilter-authorstances-stance';
    public final const COMPONENT_DELEGATORFILTER_STANCES_GENERALSTANCE = 'delegatorfilter-stances-generalstance';
    public final const COMPONENT_DELEGATORFILTER_TAGSTANCES = 'delegatorfilter-tagstances';
    public final const COMPONENT_DELEGATORFILTER_TAGSTANCES_STANCE = 'delegatorfilter-tagstances-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DELEGATORFILTER_STANCES],
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORSTANCES],
            [self::class, self::COMPONENT_DELEGATORFILTER_MYSTANCES],
            [self::class, self::COMPONENT_DELEGATORFILTER_STANCES_AUTHORROLE],
            [self::class, self::COMPONENT_DELEGATORFILTER_STANCES_STANCE],
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORSTANCES_STANCE],
            [self::class, self::COMPONENT_DELEGATORFILTER_STANCES_GENERALSTANCE],
            [self::class, self::COMPONENT_DELEGATORFILTER_TAGSTANCES],
            [self::class, self::COMPONENT_DELEGATORFILTER_TAGSTANCES_STANCE],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_STANCES => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES],
            self::COMPONENT_DELEGATORFILTER_AUTHORSTANCES => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES],
            self::COMPONENT_DELEGATORFILTER_MYSTANCES => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYSTANCES],
            self::COMPONENT_DELEGATORFILTER_STANCES_AUTHORROLE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_AUTHORROLE],
            self::COMPONENT_DELEGATORFILTER_STANCES_STANCE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE],
            self::COMPONENT_DELEGATORFILTER_AUTHORSTANCES_STANCE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORSTANCES_STANCE],
            self::COMPONENT_DELEGATORFILTER_STANCES_GENERALSTANCE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_GENERALSTANCE],
            self::COMPONENT_DELEGATORFILTER_TAGSTANCES => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES],
            self::COMPONENT_DELEGATORFILTER_TAGSTANCES_STANCE => [PoPVP_Module_Processor_CustomSimpleFilterInners::class, PoPVP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_STANCES_STANCE],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



