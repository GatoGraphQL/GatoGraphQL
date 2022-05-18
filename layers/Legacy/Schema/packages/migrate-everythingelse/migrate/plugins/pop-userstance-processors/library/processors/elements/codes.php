<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Custom_Module_Processor_Codes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_CODE_REFERENCEDAFTERREADING = 'code-referencedafterreading';
    public final const MODULE_CODE_AUTHORREFERENCEDAFTERREADING = 'code-authorreferencedafterreading';
    public final const MODULE_CODE_STANCECOUNT_GENERAL = 'code-stancecount-general';
    public final const MODULE_CODE_STANCECOUNT_ARTICLE = 'code-stancecount-article';
    public final const MODULE_CODE_STANCECOUNT = 'code-stancecount';
    public final const MODULE_CODE_POSTSTANCE = 'code-poststance';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CODE_REFERENCEDAFTERREADING],
            [self::class, self::MODULE_CODE_AUTHORREFERENCEDAFTERREADING],
            [self::class, self::MODULE_CODE_STANCECOUNT_GENERAL],
            [self::class, self::MODULE_CODE_STANCECOUNT_ARTICLE],
            [self::class, self::MODULE_CODE_STANCECOUNT],
            [self::class, self::MODULE_CODE_POSTSTANCE],
        );
    }

    public function getCode(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CODE_REFERENCEDAFTERREADING:
                return TranslationAPIFacade::getInstance()->__('After reading', 'pop-userstance-processors');

            case self::MODULE_CODE_AUTHORREFERENCEDAFTERREADING:
                return sprintf(
                    '<span class="pop-pulltextleft">%s</span>',
                    TranslationAPIFacade::getInstance()->__(', after reading', 'pop-userstance-processors')
                );

            case self::MODULE_CODE_STANCECOUNT_GENERAL:
                return sprintf(
                    '<strong>%s</strong>',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('%s: ', 'pop-userstance-processors'),
                        PoP_UserStance_PostNameUtils::getNamesUc()
                    )
                );

            case self::MODULE_CODE_STANCECOUNT_ARTICLE:
                return sprintf(
                    '<strong>%s</strong>',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('Article-related %s: ', 'pop-userstance-processors'),
                        PoP_UserStance_PostNameUtils::getNamesLc()
                    )
                );

            case self::MODULE_CODE_STANCECOUNT:
                return sprintf(
                    '<strong>%s</strong>',
                    TranslationAPIFacade::getInstance()->__('Combined: ', 'pop-userstance-processors')
                );

            case self::MODULE_CODE_POSTSTANCE:
                return sprintf(
                    '<em>%s</em>',
                    TranslationAPIFacade::getInstance()->__('Stance from our users: ', 'pop-userstance-processors')
                );
        }

        return parent::getCode($componentVariation, $props);
    }

    public function getHtmlTag(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CODE_REFERENCEDAFTERREADING:
            case self::MODULE_CODE_AUTHORREFERENCEDAFTERREADING:
                // case self::MODULE_CODE_STANCECOUNT_GENERAL:
                // case self::MODULE_CODE_STANCECOUNT_ARTICLE:
                // case self::MODULE_CODE_STANCECOUNT:
            case self::MODULE_CODE_POSTSTANCE:
                return 'span';
        }

        return parent::getHtmlTag($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
         // case self::MODULE_CODE_STANCECOUNT_GENERAL:
         // case self::MODULE_CODE_STANCECOUNT_ARTICLE:
         // case self::MODULE_CODE_STANCECOUNT:
            case self::MODULE_CODE_POSTSTANCE:
                $this->appendProp($componentVariation, $props, 'class', 'btn btn-span');
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_CODE_STANCECOUNT:
                $this->appendProp($componentVariation, $props, 'class', 'pop-stance-combined');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


