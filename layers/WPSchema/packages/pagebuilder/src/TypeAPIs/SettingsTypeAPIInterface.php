<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeAPIs;

interface PageBuilderTypeAPIInterface
{
    public function isGutenbergEditorEnabled(): bool;
}
