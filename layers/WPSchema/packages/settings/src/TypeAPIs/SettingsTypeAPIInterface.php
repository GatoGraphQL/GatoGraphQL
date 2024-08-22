<?php

declare(strict_types=1);

namespace PoPWPSchema\Settings\TypeAPIs;

interface SettingsTypeAPIInterface
{
    public function isGutenbergEditorEnabled(): bool;
}
