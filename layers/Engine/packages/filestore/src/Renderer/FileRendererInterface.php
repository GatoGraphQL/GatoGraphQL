<?php

declare(strict_types=1);

namespace PoP\FileStore\Renderer;

use PoP\FileStore\File\AbstractAccessibleRenderableFile;

interface FileRendererInterface
{
    public function render(AbstractAccessibleRenderableFile $file): string;
    public function renderAndSave(AbstractAccessibleRenderableFile $file): void;
}
