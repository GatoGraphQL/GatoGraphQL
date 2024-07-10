<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\App\Architecture\Downgrade\PHP81\ObserveDowngradeClassSample;
use ReflectionClass;
use ReflectionProperty;

/**
 * Indicate how the Application has been coded/compiled.
 */
class AppArchitecture implements AppArchitectureInterface
{
    protected static ?bool $isDowngraded = null;

    public static function isDowngraded(): bool
    {
        if (self::$isDowngraded === null) {
            self::$isDowngraded = static::calculateIsDowngraded();
        }
        return self::$isDowngraded;
    }

    /**
     * We use Reflection to find out if the code has been downgraded.
     *
     * The code uses PHP 8.1. If it has been downgraded, then no feature
     * from this PHP version will be available. So the strategy is to
     * use a sample class (`ObserveDowngradeClassSample`) that contains
     * that originally uses that feature, and check if it still has it
     * or not.
     */
    protected static function calculateIsDowngraded(): bool
    {
        /**
         * If constant ReflectionClass::IS_READONLY does not exist,
         * then the environment is not running PHP 8.1, and then
         * the code has necessarily been downgraded.
         *
         * @see https://www.php.net/manual/en/class.reflectionproperty.php#reflectionproperty.constants.modifiers
         */
        $reflectionClass = new ReflectionClass(ReflectionProperty::class);
        if (!$reflectionClass->hasConstant('IS_READONLY')) {
            return true;
        }

        /**
         * If the property is "readonly" then it's PHP 8.1.
         * Otherwise, it's been downgraded.
         */
        $reflectionClass = new ReflectionClass(ObserveDowngradeClassSample::class);
        $reflectionProperty = $reflectionClass->getProperty('property');
        return !$reflectionProperty->isReadOnly();
    }
}
