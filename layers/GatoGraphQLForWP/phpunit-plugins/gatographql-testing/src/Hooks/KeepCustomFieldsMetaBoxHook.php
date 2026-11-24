<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Hooks;

use function add_filter;

/**
 * Keep WordPress's Custom Fields box in the editor, so the meta of the
 * seeded entries is visible on the devservers.
 *
 * ACF removes the box by default, also when another plugin bundles it
 * (eg: Bricks Advanced Themer ships its own copy of ACF Pro).
 */
class KeepCustomFieldsMetaBoxHook
{
    public function __construct()
    {
        add_filter('acf/settings/remove_wp_meta_box', '__return_false');
    }
}
