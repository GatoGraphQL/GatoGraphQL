<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_Codes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_CODE_HOMEWELCOME = 'code-homewelcome';
    public final const COMPONENT_CODE_EMPTY = 'code-empty';
    public final const COMPONENT_CODE_TRENDINGTAGSDESCRIPTION = 'code-trendingtagsdescription';
    public final const COMPONENT_CODE_404 = 'code-404';
    public final const COMPONENT_CODE_EMPTYSIDEINFO = 'code-emptysideinfo';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CODE_HOMEWELCOME,
            self::COMPONENT_CODE_EMPTY,
            self::COMPONENT_CODE_TRENDINGTAGSDESCRIPTION,
            self::COMPONENT_CODE_404,
            self::COMPONENT_CODE_EMPTYSIDEINFO,
        );
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_CODE_EMPTYSIDEINFO:
                // Comment Leo 07/12/2017: this function closes the sideinfo for self::COMPONENT_CODE_EMPTYSIDEINFO, and it must take place immediately,
                // or otherwise the sideinfo will show and then disappear a few seconds later and it looks ugly (eg: in Verticals homepage, where there is no sideinfo)
                $this->addJsmethod($ret, 'closePageSectionOnTabpaneShown', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
                break;
        }

        return $ret;
    }

    public function getHtmlTag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CODE_404:
                return 'p';
        }

        return parent::getHtmlTag($component, $props);
    }

    public function getCode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $question = '<h4>%s</h4>';
        $response = '<p>%s</p><hr/>';
        $response_last = '<p>%s</p>';
        $li = '<li>%s</li>';

        switch ($component->name) {
            case self::COMPONENT_CODE_HOMEWELCOME:
                $code = sprintf(
                    '<h3 class="media-heading">%s</h3>',
                    PoP_ApplicationProcessors_Utils::getWelcomeTitle(false)
                );
                $imgcode = '';
                if ($img = gdImagesWelcome()) {
                    $imgattr = gdImagesAttributes();
                    $imgcode = sprintf(
                        '<img src="%s" class="img-responsive" '.$imgattr.'>',
                        $img
                    );
                }
                $code .= \PoP\Root\App::applyFilters('PoP_Module_Processor_Codes:description:welcomeImage', $imgcode);
                $code .= gdGetWebsiteDescription(false);
                return $code;

            case self::COMPONENT_CODE_TRENDINGTAGSDESCRIPTION:
                return sprintf(
                    '<div class="bg-warning text-warning">%s</div>',
                    \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_Codes:getCode:message',
                        sprintf(
                            TranslationAPIFacade::getInstance()->__('<strong>#Trending tags are:</strong><br/>Those tags which appear in the highest number of posts, during the last %s days.', 'poptheme-wassup'),
                            POP_TRENDINGTAGS_DAYS_TRENDINGTAGS
                        ),
                        $component
                    )
                );

            case self::COMPONENT_CODE_404:
                return TranslationAPIFacade::getInstance()->__('Oops, page not found.', 'pop-application-processors');
        }

        return parent::getCode($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CODE_HOMEWELCOME:
                $this->appendProp($component, $props, 'class', 'block-homewelcome');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


