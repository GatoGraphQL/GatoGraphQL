<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

// use GraphQLAPI\GraphQLAPI\ComponentConfiguration;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface;

abstract class AbstractModuleResolver implements ModuleResolverInterface
{
    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        return [];
    }

    public function areRequirementsSatisfied(string $module): bool
    {
        return true;
    }

    public function canBeDisabled(string $module): bool
    {
        return true;
    }

    public function isHidden(string $module): bool
    {
        return false;
    }

    public function getID(string $module): string
    {
        $moduleID = strtolower($module);
        // $moduleID = strtolower(str_replace(
        //     ['/', ' '],
        //     '-',
        //     $moduleID
        // ));
        /**
         * Replace all the "\" from the namespace with "_"
         * Otherwise there is problem when encoding/decoding,
         * since "\" is encoded as "\\".
         * Do not use "." because it can't be used as an HTML ID
         */
        return str_replace(
            '\\', //['\\', '/', ' '],
            '_',
            $moduleID
        );
    }

    public function getDescription(string $module): string
    {
        return '';
    }

    /**
     * Name of the setting item, to store in the DB
     */
    public function getSettingOptionName(string $module, string $option): string
    {
        // Use slug to remove the "\" which can create trouble
        return $this->getSlug($module) . '_' . $option;
    }

    public function hasSettings(string $module): bool
    {
        return !empty($this->getSettings($module));
    }

    /**
     * Array with key as the name of the setting, and value as its definition:
     * type (input, checkbox, select), enum values (if it is a select)
     *
     * @return array<array> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        return [];
    }

    /**
     * Indicate if the given value is valid for that option
     *
     * @param string $module
     * @param string $option
     * @param mixed $value
     * @return bool
     */
    public function isValidValue(string $module, string $option, $value): bool
    {
        return true;
    }

    /**
     * Default value for an option set by the module
     *
     * @param string $module
     * @param string $option
     * @return mixed Anything the setting might be: an array|string|bool|int|null
     */
    public function getSettingsDefaultValue(string $module, string $option)
    {
        return null;
    }

    public function isEnabledByDefault(string $module): bool
    {
        return true;
    }

    // /**
    //  * By default, point to https://graphql-api.com/modules/{component-slug}
    //  *
    //  * @param string $module
    //  * @return string|null
    //  */
    // public function getURL(string $module): ?string
    // {
    //     $moduleSlug = $this->getSlug($module);
    //     $moduleURLBase = $this->getURLBase($module);
    //     return \trailingslashit($moduleURLBase) . $moduleSlug . '/';
    // }

    /**
     * By default, the slug is the module's name, without the owner/package
     */
    public function getSlug(string $module): string
    {
        $pos = strrpos($module, '\\');
        if ($pos !== false) {
            return substr($module, $pos + strlen('\\'));
        }
        return $module;
    }

    // /**
    //  * Return the default URL base for the module, defined through configuration
    //  * By default, point to https://graphql-api.com/modules/{component-slug}
    //  *
    //  * @param string $module
    //  * @return string
    //  */
    // protected function getURLBase(string $module): string
    // {
    //     return ComponentConfiguration::getModuleURLBase();
    // }

    /**
     * Does the module have HTML Documentation?
     *
     * @param string $module
     * @return bool
     */
    public function hasDocumentation(string $module): bool
    {
        return !empty($this->getDocumentation($module));
    }

    /**
     * HTML Documentation for the module
     *
     * @param string $module
     * @return string|null
     */
    public function getDocumentation(string $module): ?string
    {
        return null;
    }
}
