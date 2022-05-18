<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_HTMLCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_HTMLCODE_HOMEWELCOMETOP = 'htmlcode-home-welcome-top';
    public final const MODULE_HTMLCODE_HOMEWELCOMEBOTTOM = 'htmlcode-home-welcome-bottom';
    public final const MODULE_HTMLCODE_HOMECOMPACTWELCOMETOP = 'htmlcode-home-compactwelcome-top';
    public final const MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM = 'htmlcode-home-compactwelcome-bottom';
    public final const MODULE_HTMLCODE_AUTHORDESCRIPTIONTOP = 'htmlcode-author-description-top';
    public final const MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM = 'htmlcode-author-description-bottom';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_HTMLCODE_HOMEWELCOMETOP],
            [self::class, self::MODULE_HTMLCODE_HOMEWELCOMEBOTTOM],
            [self::class, self::MODULE_HTMLCODE_HOMECOMPACTWELCOMETOP],
            [self::class, self::MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM],
            [self::class, self::MODULE_HTMLCODE_AUTHORDESCRIPTIONTOP],
            [self::class, self::MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM],
        );
    }

    public function getHtmlTag(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_HTMLCODE_HOMEWELCOMETOP:
            case self::MODULE_HTMLCODE_HOMECOMPACTWELCOMETOP:
            case self::MODULE_HTMLCODE_AUTHORDESCRIPTIONTOP:
            case self::MODULE_HTMLCODE_HOMEWELCOMEBOTTOM:
            case self::MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM:
            case self::MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM:
                return null;
        }

        return parent::getHtmlTag($componentVariation, $props);
    }

    protected function isStaticCode(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_HTMLCODE_HOMEWELCOMETOP:
            case self::MODULE_HTMLCODE_HOMECOMPACTWELCOMETOP:
            case self::MODULE_HTMLCODE_AUTHORDESCRIPTIONTOP:
            case self::MODULE_HTMLCODE_HOMEWELCOMEBOTTOM:
            case self::MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM:
            case self::MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM:
                return false;
        }

        return parent::isStaticCode($componentVariation, $props);
    }

    public function getCode(array $componentVariation, array &$props)
    {

        // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            switch ($componentVariation[1]) {
                case self::MODULE_HTMLCODE_HOMEWELCOMETOP:
                case self::MODULE_HTMLCODE_HOMECOMPACTWELCOMETOP:
                case self::MODULE_HTMLCODE_AUTHORDESCRIPTIONTOP:
                    $subComponentVariations = array(
                        self::MODULE_HTMLCODE_HOMEWELCOMETOP => [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_HOMEWELCOME],
                        self::MODULE_HTMLCODE_HOMECOMPACTWELCOMETOP => [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_HOMEWELCOME],
                        self::MODULE_HTMLCODE_AUTHORDESCRIPTIONTOP => [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_AUTHOR_CONTENT],
                    );
                    $subComponentVariation = $subComponentVariations[$componentVariation[1]];

                    // This value must be set by the parent module
                    $target_id = $this->getProp($componentVariation, $props, 'target-id');
                    $target = '#'.$target_id;
                    $description = sprintf(
                        '<span id="%s" class="top-collapse"><a data-toggle="collapse" href="%s" aria-expanded="false" class="close" title="%s">%s</a></span>',
                        $target_id.'-collapse',
                        $target,
                        TranslationAPIFacade::getInstance()->__('Close', 'poptheme-wassup'),
                        '<i class="fa fa-close"></i>'
                    );

                    if ($componentVariation == [self::class, self::MODULE_HTMLCODE_HOMEWELCOMETOP] || $componentVariation == [self::class, self::MODULE_HTMLCODE_HOMECOMPACTWELCOMETOP]) {
                             // Allow qTrans to add the language links
                        $description = \PoP\Root\App::applyFilters(
                            'PoP_Module_Processor_HTMLCodes:homewelcometitle',
                            $description,
                            $componentVariation
                        );
                    }
                    return $description;

                case self::MODULE_HTMLCODE_HOMEWELCOMEBOTTOM:
                case self::MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM:
                case self::MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM:
                    $welcometitle = PoP_ApplicationProcessors_Utils::getWelcomeTitle();
                    $titles = array(
                        self::MODULE_HTMLCODE_HOMEWELCOMEBOTTOM => $welcometitle,
                        self::MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM => $welcometitle,
                        self::MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM => '<i class="fa fa-fw fa-info"></i>'.TranslationAPIFacade::getInstance()->__('Show description', 'poptheme-wassup'),
                    );
                    $markups = array(
                        self::MODULE_HTMLCODE_HOMEWELCOMEBOTTOM => 'h2',
                        self::MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM => 'h4',
                        self::MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM => 'h4',
                    );
                    $classes = array(
                        self::MODULE_HTMLCODE_HOMEWELCOMEBOTTOM => '',
                        self::MODULE_HTMLCODE_HOMECOMPACTWELCOMEBOTTOM => '',
                        self::MODULE_HTMLCODE_AUTHORDESCRIPTIONBOTTOM => 'btn btn-default btn-block btn-lg',
                    );

                    // This value must be set by the parent module
                    $target_id = $this->getProp($componentVariation, $props, 'target-id');
                    $target = '#'.$target_id;

                    $welcometitle = sprintf(
                        '<a data-toggle="collapse" href="%s" aria-expanded="false" class="%s">%s</a>',
                        $target,
                        $classes[$componentVariation[1]],
                        $titles[$componentVariation[1]].' <i class="fa fa-angle-down"></i>'
                    );

                    $welcome = sprintf(
                        '<%1$s id="%2$s" class="top-expand text-center">%3$s</%1$s>',
                        $markups[$componentVariation[1]],
                        $target_id.'-expand',
                        $welcometitle
                    );

                    return $welcome;
            }
        }

        return parent::getCode($componentVariation, $props);
    }
}


