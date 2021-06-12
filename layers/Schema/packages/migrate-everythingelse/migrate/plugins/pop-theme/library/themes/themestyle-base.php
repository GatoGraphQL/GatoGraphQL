<?php
namespace PoP\Theme\Themes;

abstract class ThemeStyleBase
{
    public function __construct()
    {
        $this->getTheme()->addThemestyle($this);
    }

    abstract public function getTheme();

    abstract public function getName(): string;
}
