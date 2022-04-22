<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

interface SettingsNormalizerInterface
{
    /**
     * Normalize the form values:
     *
     * - If the input is empty, replace with the default
     * - Convert from string to int/bool
     *
     * @param array<string, string> $value All values submitted, each under its optionName as key
     * @return array<string, mixed> Normalized values
     */
    public function normalizeSettings(array $value): array;
    /**
     * Return all the modules with settings
     *
     * @return array<array> Each item is an array of prop => value
     */
    public function getAllSettingsItems(): array;
}
