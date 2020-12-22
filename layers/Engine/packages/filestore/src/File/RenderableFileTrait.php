<?php

declare(strict_types=1);

namespace PoP\FileStore\File;

trait RenderableFileTrait
{
    /**
     * @var AbstractRenderableFileFragment[]|null
     */
    private ?array $fragments = null;

    /**
     * @return AbstractRenderableFileFragment[]
     */
    public function getFragments(): array
    {
        if (is_null($this->fragments)) {
            $this->fragments = $this->getFragmentObjects();
        }

        return $this->fragments;
    }

    /**
     * @return AbstractRenderableFileFragment[]
     */
    abstract protected function getFragmentObjects(): array;
}
