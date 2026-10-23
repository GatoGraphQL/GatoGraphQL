<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use function count;
use function explode;
use function in_array;
use function is_string;
use function trim;

/**
 * The button beside a license key activates, validates or deactivates
 * that license only, while the form submits all the keys.
 */
class LicenseActionHelpers
{
    public final const ACTION_ACTIVATE = 'activate';
    public final const ACTION_VALIDATE = 'validate';
    public final const ACTION_DEACTIVATE = 'deactivate';

    /**
     * The value that the button of the action on that license submits
     */
    public static function getActionValue(string $action, string $extensionSlug): string
    {
        return $action . ':' . $extensionSlug;
    }

    /**
     * From the value submitted by the button, the license keys to act
     * on (only that license's, before and after), and all the license
     * keys to store: the stored ones, with only that license's changed.
     * Null when the value is not that of an action.
     *
     * @param array<string,mixed> $storedLicenseKeys
     * @param array<string,mixed> $submittedLicenseKeys
     * @return array{previous:array<string,string>,submitted:array<string,string>,store:array<string,mixed>}|null
     */
    public static function getLicenseKeysForAction(
        mixed $actionValue,
        array $storedLicenseKeys,
        array $submittedLicenseKeys,
    ): ?array {
        if (!is_string($actionValue)) {
            return null;
        }
        $parts = explode(':', $actionValue, 2);
        if (count($parts) !== 2 || $parts[1] === '') {
            return null;
        }
        [$action, $extensionSlug] = $parts;
        if (!in_array($action, [self::ACTION_ACTIVATE, self::ACTION_VALIDATE, self::ACTION_DEACTIVATE], true)) {
            return null;
        }
        $storedLicenseKey = $storedLicenseKeys[$extensionSlug] ?? '';
        $storedLicenseKey = is_string($storedLicenseKey) ? trim($storedLicenseKey) : '';
        $licenseKey = '';
        if ($action !== self::ACTION_DEACTIVATE) {
            $submittedLicenseKey = $submittedLicenseKeys[$extensionSlug] ?? '';
            $licenseKey = is_string($submittedLicenseKey) ? trim($submittedLicenseKey) : '';
        }
        $store = $storedLicenseKeys;
        $store[$extensionSlug] = $licenseKey;
        return [
            'previous' => [$extensionSlug => $storedLicenseKey],
            'submitted' => [$extensionSlug => $licenseKey],
            'store' => $store,
        ];
    }
}
