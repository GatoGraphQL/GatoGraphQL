<?php

define('GD_THEMESTYLE_WASSUP_EXPANSIVE', 'expansive');

class GD_ThemeMode_Wassup_Expansive extends GD_WassupThemeStyle_Base
{
    public function __construct()
    {
        
        // Hooks to allow the themestyles to do some functionality
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_CAROUSEL_USERS_GRIDCLASS.':'.$this->getTheme()->getName().':'.$this->getName(), array($this, 'getCarouselUsersGridclass'));
        \PoP\Root\App::getHookManager()->addFilter(POP_HOOK_SCROLLINNER_THUMBNAIL_GRID.':'.$this->getTheme()->getName().':'.$this->getName(), array($this, 'getScrollinnerThumbnailGrid'));
        
        parent::__construct();
    }

    public function getName(): string
    {
        return GD_THEMESTYLE_WASSUP_EXPANSIVE;
    }

    public function getScrollinnerThumbnailGrid($grid)
    {
        return array(
            'row-items' => 3,
            'class' => 'col-xsm-4'
        );
    }

    public function getCarouselUsersGridclass($class)
    {
        return 'col-xs-4 col-sm-6 col-md-4 no-padding';
    }
}

/**
 * Initialization
 */
new GD_ThemeMode_Wassup_Expansive();
