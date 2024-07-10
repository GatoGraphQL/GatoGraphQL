<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface SettingsNormalizerInterface
{
    /**
     * Normalize the form values:
     *
     * - If the input is empty, replace with the default
     * - Convert from string to int/bool
     *
     * @param array<string,string> $values All values submitted, each under its optionName as key
     * @return array<string,mixed> Normalized values
     */
    public function normalizeSettingsByCategory(
        array $values,
        string $settingsCategory,
    ): array;
    /**
     * Normalize the form values:
     *
     * - If the input is empty, replace with the default
     * - Convert from string to int/bool
     *
     * @param array<string,string> $values All values submitted, each under its optionName as key
     * @return array<string,mixed> Normalized values
     */
    public function normalizeSettingsByModule(
        array $values,
        string $module,
    ): array;
    /**
     * Normalize the form values for a specific module.
     *
     * This method sets the previous values properly when
     * called from the REST API (eg: when executing Integration Tests)
     * as the previousValue for some Settings option could be non-existent,
     * and it must be overridden/removed.
     *
     * @param array<string,string> $values All values submitted, each under its optionName as key
     * @return array<string,mixed> Normalized values
     */
    public function normalizeSettingsForRESTAPIController(string $module, array $values): array;
    /**
     * Return all the modules with settings
     *
     * @return array<array<string,mixed>> Each item is an array of prop => value
     */
    public function getAllSettingsItems(?string $settingsCategory = null): array;
}
