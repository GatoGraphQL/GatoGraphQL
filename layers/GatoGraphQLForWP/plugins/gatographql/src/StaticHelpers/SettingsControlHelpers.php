<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;

use function array_is_list;
use function array_map;
use function count;
use function in_array;
use function is_array;
use function is_bool;
use function is_scalar;
use function ksort;
use function mb_strlen;
use function preg_match;
use function sort;
use function strip_tags;
use function trim;

/**
 * How the Settings page draws each setting, decided from the
 * setting's own definition in its ModuleResolver.
 */
class SettingsControlHelpers
{
    /**
     * Up to this many options, each this short at most, are shown
     * all at once as a segmented control, instead of a dropdown
     */
    public final const SEGMENTED_MAX_OPTIONS = 4;
    public final const SEGMENTED_MAX_LABEL_LENGTH = 22;
    public final const GRID_SEGMENTED_MAX_OPTIONS = 3;
    public final const GRID_SEGMENTED_MAX_LABEL_LENGTH = 20;

    /**
     * The control for the setting: the one forced via `Properties::CONTROL`,
     * or else the one that fits its type and values.
     *
     * @param array<string,mixed> $itemSetting
     */
    public static function getControl(array $itemSetting): string
    {
        /** @var string|null */
        $control = $itemSetting[Properties::CONTROL] ?? null;
        if ($control !== null) {
            return $control;
        }

        $type = $itemSetting[Properties::TYPE] ?? null;
        /** @var array<string|int,string> */
        $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
        /** @var array<string|int,string> */
        $keyLabels = $itemSetting[Properties::KEY_LABELS] ?? [];

        if ($type === Properties::TYPE_NULL) {
            return self::getNullTypeControl($itemSetting);
        }
        if ($possibleValues !== [] && $keyLabels === []) {
            if ($itemSetting[Properties::IS_MULTIPLE] ?? false) {
                return Properties::CONTROL_CHECKLIST;
            }
            if (
                !($itemSetting[Properties::APPEND_VALUE_IF_NON_EXISTING] ?? false)
                && self::isShortOptionList($possibleValues, self::SEGMENTED_MAX_OPTIONS, self::SEGMENTED_MAX_LABEL_LENGTH)
            ) {
                return Properties::CONTROL_SEGMENTED;
            }
            return Properties::CONTROL_SELECT;
        }
        if ($type === Properties::TYPE_ARRAY) {
            return Properties::CONTROL_TAGS;
        }
        if ($type === Properties::TYPE_BOOL) {
            return Properties::CONTROL_SWITCH;
        }
        if ($type === Properties::TYPE_PROPERTY_ARRAY) {
            if ($possibleValues === [] && ($itemSetting[Properties::SUBTYPE] ?? null) === Properties::TYPE_BOOL) {
                return Properties::CONTROL_CHIPS;
            }
            return Properties::CONTROL_GRID;
        }
        if ($itemSetting[Properties::USE_TEXTAREA] ?? false) {
            return Properties::CONTROL_TEXTAREA;
        }
        return Properties::CONTROL_INPUT;
    }

    /**
     * A TYPE_NULL item stores nothing. It is either:
     *
     * - The heading of a group of settings: it has a scope, or its
     *   title is all underlined (the way headings were written before)
     * - A divider: no title, and only a horizontal rule
     * - Content to show (a note, buttons, a script)
     *
     * @param array<string,mixed> $itemSetting
     */
    protected static function getNullTypeControl(array $itemSetting): string
    {
        /** @var string */
        $title = $itemSetting[Properties::TITLE] ?? '';
        /** @var string */
        $description = $itemSetting[Properties::DESCRIPTION] ?? '';
        if (
            isset($itemSetting[Properties::SCOPE])
            || preg_match('#^\s*<u>.*</u>\s*$#s', $title) === 1
        ) {
            return Properties::CONTROL_GROUP;
        }
        if (trim($title) === '' && preg_match('#^\s*<hr\s*/?>\s*$#i', $description) === 1) {
            return Properties::CONTROL_DIVIDER;
        }
        return Properties::CONTROL_CONTENT;
    }

    /**
     * The control for each entry within a grid (one per key)
     *
     * @param array<string,mixed> $itemSetting
     */
    public static function getGridEntryControl(array $itemSetting): string
    {
        /** @var array<string|int,string> */
        $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
        if ($possibleValues === []) {
            return Properties::CONTROL_INPUT;
        }
        if ($itemSetting[Properties::IS_MULTIPLE] ?? false) {
            return Properties::CONTROL_SELECT;
        }
        if (self::isShortOptionList($possibleValues, self::GRID_SEGMENTED_MAX_OPTIONS, self::GRID_SEGMENTED_MAX_LABEL_LENGTH)) {
            return Properties::CONTROL_SEGMENTED;
        }
        return Properties::CONTROL_SELECT;
    }

    /**
     * @param array<string|int,string> $possibleValues
     */
    public static function isShortOptionList(array $possibleValues, int $maxOptions, int $maxLabelLength): bool
    {
        $count = count($possibleValues);
        if ($count < 2 || $count > $maxOptions) {
            return false;
        }
        foreach ($possibleValues as $label) {
            if (mb_strlen(trim(strip_tags($label))) > $maxLabelLength) {
                return false;
            }
        }
        return true;
    }

    /**
     * Split the settings of a module into groups: each heading
     * starts a group, and so does a divider, after some setting.
     * The settings before the first heading form a group without
     * a heading.
     *
     * @param array<array<string,mixed>> $itemSettings
     * @return array<array{heading:array<string,mixed>|null,settings:array<array<string,mixed>>}>
     */
    public static function getGroups(array $itemSettings): array
    {
        $groups = [];
        $group = ['heading' => null, 'settings' => []];
        foreach ($itemSettings as $itemSetting) {
            $control = self::getControl($itemSetting);
            if ($control === Properties::CONTROL_GROUP) {
                $groups[] = $group;
                $group = ['heading' => $itemSetting, 'settings' => []];
                continue;
            }
            if ($control === Properties::CONTROL_DIVIDER) {
                if ($group['settings'] !== []) {
                    $groups[] = $group;
                    $group = ['heading' => null, 'settings' => []];
                }
                continue;
            }
            $group['settings'][] = $itemSetting;
        }
        $groups[] = $group;

        $nonEmptyGroups = [];
        foreach ($groups as $group) {
            if (
                $group['settings'] === []
                && ($group['heading'] === null || trim((string) ($group['heading'][Properties::DESCRIPTION] ?? '')) === '')
            ) {
                continue;
            }
            $nonEmptyGroups[] = $group;
        }
        return $nonEmptyGroups;
    }

    /**
     * Whether the stored value is other than the default one.
     * Lists are compared in any order, and a boolean against
     * the truthiness of the other value.
     */
    public static function differsFromDefault(mixed $value, mixed $defaultValue): bool
    {
        return self::normalizeForComparison($value) !== self::normalizeForComparison($defaultValue);
    }

    protected static function normalizeForComparison(mixed $value): mixed
    {
        if (is_bool($value)) {
            return $value ? '1' : '';
        }
        if ($value === null) {
            return '';
        }
        if (is_array($value)) {
            if (array_is_list($value)) {
                $normalized = array_map(self::normalizeForComparison(...), $value);
                sort($normalized);
                return $normalized;
            }
            $normalized = array_map(self::normalizeForComparison(...), $value);
            ksort($normalized);
            return $normalized;
        }
        if (is_scalar($value)) {
            return (string) $value;
        }
        return $value;
    }

    /**
     * Whether the option is the current value. Option values come from
     * array keys, which PHP casts to int when numeric, so compare them
     * as strings.
     *
     * @param mixed[] $currentValues
     */
    public static function isSelectedValue(string|int $optionValue, array $currentValues): bool
    {
        return in_array((string) $optionValue, array_map(
            fn (mixed $value) => is_bool($value) ? ($value ? '1' : '') : (is_scalar($value) ? (string) $value : ''),
            $currentValues
        ), true);
    }

    public static function isNumberOutOfRange(int $value, ?int $minNumber, ?int $maxNumber): bool
    {
        return ($minNumber !== null && $value < $minNumber)
            || ($maxNumber !== null && $value > $maxNumber);
    }

    /**
     * The number of values in the submitted form: each leaf of
     * the (possibly nested) array counts as one.
     *
     * @param array<mixed> $values
     */
    public static function countLeafValues(array $values): int
    {
        $count = 0;
        foreach ($values as $value) {
            if (is_array($value)) {
                $count += self::countLeafValues($value);
                continue;
            }
            $count++;
        }
        return $count;
    }
}
