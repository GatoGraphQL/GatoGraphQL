<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace\Enums;

enum LicenseStatus: string
{
    /** The license key has one or more activations */
    case ACTIVE = 'active';

    /** The license key's expiry date has passed, either because the related product had a defined license length or because the license's subscription has expired */
    case EXPIRED = 'expired';

    /** The license key is valid but has no activations */
    case INACTIVE = 'inactive';

    /** The license key has been manually disabled */
    case DISABLED = 'disabled';

    /** The license key has not been registered/activated on this site */
    case UNREGISTERED = 'unregistered';

    /** The license is "inactive", "disabled", or any other */
    case OTHER = 'other';
}
