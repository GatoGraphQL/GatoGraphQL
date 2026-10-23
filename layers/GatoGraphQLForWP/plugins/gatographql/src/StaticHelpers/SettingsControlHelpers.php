<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;

use function array_map;
use function count;
use function implode;
use function in_array;
use function is_array;
use function is_bool;
use function is_scalar;
use function json_decode;
use function json_encode;
use function json_last_error;
use function max;
use function mb_strlen;
use function mb_strtolower;
use function mb_substr;
use function min;
use function preg_match;
use function preg_replace;
use function preg_replace_callback;
use function strip_tags;
use function substr_count;
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
    public final const SEGMENTED_MAX_OPTIONS = 5;
    public final const SEGMENTED_MAX_LABEL_LENGTH = 24;
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
        if (preg_match('#^\s*<u>.*</u>\s*$#s', $title) === 1) {
            return Properties::CONTROL_GROUP;
        }
        if (trim($title) === '' && preg_match('#^\s*<hr\s*/?>\s*$#i', $description) === 1) {
            return Properties::CONTROL_DIVIDER;
        }
        return Properties::CONTROL_CONTENT;
    }

    /**
     * A field to type free text in (eg: a message, a URL), which
     * takes the whole width of the row, as its definition asks.
     * Short values (an ID, a name, a number) stay beside the title.
     *
     * @param array<string,mixed> $itemSetting
     */
    public static function isFullWidthInput(array $itemSetting): bool
    {
        return self::getControl($itemSetting) === Properties::CONTROL_INPUT
            && ($itemSetting[Properties::FULL_WIDTH] ?? false);
    }

    /**
     * Split the description of a setting into its one line, and the
     * help it holds behind "Show details" (as printed by
     * `getCollapsible`), which goes behind the "i" button instead.
     *
     * @return array{description:string,help:string}
     */
    public static function splitDescriptionHelp(string $description): array
    {
        $helps = [];
        $description = (string) preg_replace_callback(
            '#<details class="gato-collapsible"><summary>.*?</summary><div class="gato-collapsible-content">(.*?)</div></details>#s',
            function (array $matches) use (&$helps): string {
                $helps[] = trim((string) preg_replace('#^(\s|<br\s*/?>)+#i', '', $matches[1]));
                return '';
            },
            $description
        );
        $description = (string) preg_replace('#(\s|<br\s*/?>)+$#i', '', $description);
        return [
            'description' => trim($description),
            'help' => implode('<br/><br/>', $helps),
        ];
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
        /** @var string|null */
        $entryControl = $itemSetting[Properties::ENTRY_CONTROL] ?? null;
        if ($entryControl !== null) {
            return $entryControl;
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
            if ($control === Properties::CONTROL_SECTION_HELP) {
                continue;
            }
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
     * What the whole section does, from its items with control
     * CONTROL_SECTION_HELP, shown behind the "i" beside its title
     *
     * @param array<array<string,mixed>> $itemSettings
     */
    public static function getSectionHelp(array $itemSettings): string
    {
        $helps = [];
        foreach ($itemSettings as $itemSetting) {
            if (self::getControl($itemSetting) !== Properties::CONTROL_SECTION_HELP) {
                continue;
            }
            /** @var string */
            $help = $itemSetting[Properties::HELP] ?? '';
            if (trim($help) !== '') {
                $helps[] = $help;
            }
        }
        return implode('', $helps);
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
     * The title without the unit it ends with in parentheses
     * (eg: "Request timeout (seconds)"), when the field shows
     * that same unit after it
     */
    public static function getTitleWithoutUnit(string $title, ?string $unit): string
    {
        if ($unit === null || $unit === '') {
            return $title;
        }
        $suffix = ' (' . $unit . ')';
        if (mb_strtolower(mb_substr($title, -mb_strlen($suffix))) !== mb_strtolower($suffix)) {
            return $title;
        }
        return mb_substr($title, 0, -mb_strlen($suffix));
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

    /**
     * A JSON value, indented to read it. A value that is not valid JSON,
     * or that indenting would write differently (eg: `1.0` printed as
     * `1`, or `"\u00e9"` as `"é"`), stays as it is.
     */
    public static function formatJSON(string $value): string
    {
        if (trim($value) === '') {
            return $value;
        }
        $decoded = json_decode($value);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $value;
        }
        $formatted = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
        if ($formatted === false || !self::isSameJSON($formatted, $value)) {
            return $value;
        }
        return $formatted;
    }

    /**
     * Whether both texts hold the same JSON, written the same but for
     * the whitespace between its tokens. A text that is not valid JSON
     * is the same only as the very same text.
     */
    public static function isSameJSON(string $value, string $otherValue): bool
    {
        if ($value === $otherValue) {
            return true;
        }
        json_decode($value);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        json_decode($otherValue);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        return self::removeJSONWhitespace($value) === self::removeJSONWhitespace($otherValue);
    }

    /**
     * The JSON text without the whitespace between its tokens (the one
     * within its strings stays)
     */
    private static function removeJSONWhitespace(string $value): string
    {
        return (string) preg_replace_callback(
            '/"(?:[^"\\\\]|\\\\.)*"|\s+/s',
            fn (array $matches): string => $matches[0][0] === '"' ? $matches[0] : '',
            $value
        );
    }

    /**
     * Rows for a JSON textarea: as many as its lines, within bounds.
     */
    public static function getJSONTextareaRows(string $value): int
    {
        return max(3, min(16, substr_count($value, "\n") + 2));
    }
}
