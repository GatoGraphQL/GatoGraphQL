<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Overrides\Services;

use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsService as UpstreamAllowOrDenySettingsService;

use function remove_accents;

/**
 * The `option_name` and `meta_key` columns use a Unicode collation which,
 * on top of ignoring letter case and trailing whitespace, treats a letter
 * and its accented variants as the same character. An entry on a denylist
 * would then not match a name such as "plugin_api_kéy", which the database
 * nevertheless resolves to the denylisted "plugin_api_key".
 */
class AllowOrDenySettingsService extends UpstreamAllowOrDenySettingsService
{
    protected function normalizeEntryName(string $name): string
    {
        return parent::normalizeEntryName(remove_accents($name));
    }
}
