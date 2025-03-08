<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeAPIs;

interface SettingsTypeAPIInterface
{
    public function isGutenbergEditorEnabled(): bool;
}
