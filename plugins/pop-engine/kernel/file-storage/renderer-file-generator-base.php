<?php
namespace PoP\Engine\FileStorage;

abstract class RendererFileGeneratorBase extends FileLocationBase
{
    public function getRenderer()
    {
        return null;
    }

    public function generate()
    {
        if ($renderer = $this->getRenderer()) {
            
            // Render and save the content
            $contents = $renderer->render();
            FileStorage_Factory::getInstance()->saveFile($this->getFilepath(), $contents);
        }
    }
}
