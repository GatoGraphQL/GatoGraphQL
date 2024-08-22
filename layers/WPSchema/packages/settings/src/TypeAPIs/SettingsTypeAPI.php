<?php

declare(strict_types=1);

namespace PoPWPSchema\Settings\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;

class SettingsTypeAPI implements SettingsTypeAPIInterface
{
    use BasicServiceTrait;

    // @todo Implement!
    public function isGutenbergEditorEnabled(): bool
    {
        return false;
    }
}
