<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\App;
use PoP\Root\Constants\HookNames;

define('GD_THEMEMODE_WASSUP_EMBED', 'embed');

class GD_ThemeMode_Wassup_Embed extends GD_ThemeMode_Wassup_Simple
{
    public function __construct()
    {

        // App::addFilter('gd_jquery_constants', $this->jqueryConstants(...));

        // Hooks to allow the thememodes to do some functionality
        App::addFilter(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER.':'.$this->getTheme()->getName().':'.$this->getName(), $this->filteringbyShowfilter(...));

        // The embed must make the main pageSection scrollable using perfect-scrollbar, so that the fullscreen mode works fine
        App::addFilter(POP_HOOK_WASSUPUTILS_SCROLLABLEMAIN.':'.$this->getTheme()->getName().':'.$this->getName(), '__return_true');

        App::addAction(HookNames::AFTER_BOOT_APPLICATION, function() {
            if (in_array(POP_STRATUM_WEB, App::getState('strata'))) {
                App::addFilter(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS.':'.$this->getTheme()->getName().':'.$this->getName(), '__return_false');
            }
        });

        parent::__construct();
    }

    // function jqueryConstants($jqueryConstants) {

    //     $jqueryConstants['THEMEMODE_WASSUP_EMBED'] = GD_THEMEMODE_WASSUP_EMBED;
    //     return $jqueryConstants;
    // }

    public function getName(): string
    {
        return GD_THEMEMODE_WASSUP_EMBED;
    }

    public function getFramepagesections($pagesections, array $module)
    {

        // Same as ThemeMode Simple, however we don't need the Navigator
        $pagesections = parent::getFramepagesections($pagesections, $module);

        array_splice(
            $pagesections,
            array_search(
                [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_SIDE],
                $pagesections
            ),
            1
        );
        array_splice(
            $pagesections,
            array_search(
                [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::MODULE_OFFCANVAS_NAVIGATOR],
                $pagesections
            ),
            1
        );

        return $pagesections;
    }

    public function filteringbyShowfilter($showfilter)
    {
        return false;
    }
}

/**
 * Initialization
 */
new GD_ThemeMode_Wassup_Embed();
