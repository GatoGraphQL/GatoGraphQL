<?php

class PoP_CSSConverter_ConversionUtils
{
    public static function getClasses()
    {
        global $pop_cssconverter_conversionmanager;
        return $pop_cssconverter_conversionmanager->getClasses();
    }

    public static function convert($class)
    {
        global $pop_cssconverter_conversionmanager;
        return $pop_cssconverter_conversionmanager->convert($class);
    }

    public static function getClassSelector($classname)
    {
        global $pop_cssconverter_conversionmanager;
        return $pop_cssconverter_conversionmanager->getClassSelector($classname);
    }

    public static function getStylesFromClasses($classes)
    {
        global $pop_cssconverter_conversionmanager;
        return $pop_cssconverter_conversionmanager->getStylesFromClasses($classes);
    }
}
