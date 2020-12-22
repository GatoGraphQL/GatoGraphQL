<?php

declare(strict_types=1);

namespace PoP\FileStore\File;

abstract class AbstractFile
{
    abstract public function getDir(): string;

    abstract public function getFilename(): string;

    public function getFilepath(): string
    {
        return $this->getDir() . '/' . $this->getFilename();
    }
}
