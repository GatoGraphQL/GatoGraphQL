<?php

declare(strict_types=1);

namespace PoP\FileStore\File;

abstract class AbstractAccessibleRenderableFile extends AbstractAccessibleFile
{
    use RenderableFileTrait;
}
