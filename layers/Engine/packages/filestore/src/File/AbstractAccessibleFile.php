<?php

declare(strict_types=1);

namespace PoP\FileStore\File;

abstract class AbstractAccessibleFile extends AbstractFile
{
    abstract public function getUrl(): string;

    public function getFileurl(): string
    {
        return $this->getUrl() . '/' . $this->getFilename();
    }
}
