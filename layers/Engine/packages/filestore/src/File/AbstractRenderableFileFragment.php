<?php

declare(strict_types=1);

namespace PoP\FileStore\File;

abstract class AbstractRenderableFileFragment
{
    abstract public function getAssetsPath(): string;

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        return array();
    }

    public function isJsonReplacement(): bool
    {
        return true;
    }

    public function getJsonEncodeOptions(): int
    {
        // Documentation: https://secure.php.net/manual/en/function.json-encode.php
        return 0;
    }
}
