<?php
namespace PoP\Theme\Themes;

abstract class ThemeModeBase
{
    public function __construct()
    {
        $this->getTheme()->addThememode($this);
    }

    abstract public function getTheme();

    abstract public function getName(): string;
}
