<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

/**
 * Static Option names.
 *
 * These options cannot be namespaced (via the OptionNamespacer service),
 * so they must all be already namespaced (with "gatographql-")
 */
class StaticOptions
{
    /**
     * Store the license data for all bundles/extensions that
     * have been activated
     */
    public final const COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES = 'commercial-extension-activated-license-entries';
}
