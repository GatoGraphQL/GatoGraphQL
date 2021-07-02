<?php

abstract class PoP_Engine_CSSFileReproductionBase extends \PoP\FileStore\File\AbstractRenderableFileFragment
{
    public function isJsonReplacement(): bool
    {
        return false;
    }
}
