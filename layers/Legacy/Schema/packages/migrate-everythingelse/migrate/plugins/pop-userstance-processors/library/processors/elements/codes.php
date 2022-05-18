<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Custom_Module_Processor_Codes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_CODE_REFERENCEDAFTERREADING = 'code-referencedafterreading';
    public final const COMPONENT_CODE_AUTHORREFERENCEDAFTERREADING = 'code-authorreferencedafterreading';
    public final const COMPONENT_CODE_STANCECOUNT_GENERAL = 'code-stancecount-general';
    public final const COMPONENT_CODE_STANCECOUNT_ARTICLE = 'code-stancecount-article';
    public final const COMPONENT_CODE_STANCECOUNT = 'code-stancecount';
    public final const COMPONENT_CODE_POSTSTANCE = 'code-poststance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CODE_REFERENCEDAFTERREADING],
            [self::class, self::COMPONENT_CODE_AUTHORREFERENCEDAFTERREADING],
            [self::class, self::COMPONENT_CODE_STANCECOUNT_GENERAL],
            [self::class, self::COMPONENT_CODE_STANCECOUNT_ARTICLE],
            [self::class, self::COMPONENT_CODE_STANCECOUNT],
            [self::class, self::COMPONENT_CODE_POSTSTANCE],
        );
    }

    public function getCode(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CODE_REFERENCEDAFTERREADING:
                return TranslationAPIFacade::getInstance()->__('After reading', 'pop-userstance-processors');

            case self::COMPONENT_CODE_AUTHORREFERENCEDAFTERREADING:
                return sprintf(
                    '<span class="pop-pulltextleft">%s</span>',
                    TranslationAPIFacade::getInstance()->__(', after reading', 'pop-userstance-processors')
                );

            case self::COMPONENT_CODE_STANCECOUNT_GENERAL:
                return sprintf(
                    '<strong>%s</strong>',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('%s: ', 'pop-userstance-processors'),
                        PoP_UserStance_PostNameUtils::getNamesUc()
                    )
                );

            case self::COMPONENT_CODE_STANCECOUNT_ARTICLE:
                return sprintf(
                    '<strong>%s</strong>',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('Article-related %s: ', 'pop-userstance-processors'),
                        PoP_UserStance_PostNameUtils::getNamesLc()
                    )
                );

            case self::COMPONENT_CODE_STANCECOUNT:
                return sprintf(
                    '<strong>%s</strong>',
                    TranslationAPIFacade::getInstance()->__('Combined: ', 'pop-userstance-processors')
                );

            case self::COMPONENT_CODE_POSTSTANCE:
                return sprintf(
                    '<em>%s</em>',
                    TranslationAPIFacade::getInstance()->__('Stance from our users: ', 'pop-userstance-processors')
                );
        }

        return parent::getCode($component, $props);
    }

    public function getHtmlTag(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CODE_REFERENCEDAFTERREADING:
            case self::COMPONENT_CODE_AUTHORREFERENCEDAFTERREADING:
                // case self::COMPONENT_CODE_STANCECOUNT_GENERAL:
                // case self::COMPONENT_CODE_STANCECOUNT_ARTICLE:
                // case self::COMPONENT_CODE_STANCECOUNT:
            case self::COMPONENT_CODE_POSTSTANCE:
                return 'span';
        }

        return parent::getHtmlTag($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
         // case self::COMPONENT_CODE_STANCECOUNT_GENERAL:
         // case self::COMPONENT_CODE_STANCECOUNT_ARTICLE:
         // case self::COMPONENT_CODE_STANCECOUNT:
            case self::COMPONENT_CODE_POSTSTANCE:
                $this->appendProp($component, $props, 'class', 'btn btn-span');
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_CODE_STANCECOUNT:
                $this->appendProp($component, $props, 'class', 'pop-stance-combined');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


