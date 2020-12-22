<?php

class PoP_ResourceLoader_FileReproduction_ResourcesMirrorCode extends \PoP\FileStore\File\AbstractRenderableFileFragment {

    private $resources;

    function setResources($resources) {

        $this->resources = $resources;
    }

    // function getRenderer() {

    //     global $pop_resourceloader_mirrorcode_renderer;
    //     return $pop_resourceloader_mirrorcode_renderer;
    // }

    public function getAssetsPath(): string {

        return POP_RESOURCELOADER_ASSETS_DIR.'/js/jobs/mirrorcode.js';
    }

    public function isJsonReplacement(): bool {

        return false;
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array {

        $configuration = parent::getConfiguration();

        global $pop_resourceloaderprocessor_manager;

        $contents = array();
        foreach ($this->resources as $resource) {

            // Get the content for that resource
            // Comment Leo 13/11/2017: use getFilePath instead of getAssetPath so that it includes the minified resources
            // $file = $pop_resourceloaderprocessor_manager->getAssetPath($resource);
            $file = $pop_resourceloaderprocessor_manager->getFilePath($resource);
            $file_contents = file_get_contents($file);
            if ($file_contents !== false) {
                $contents[] = $file_contents;
            }
        }
        $configuration['$contents'] = implode(PHP_EOL, $contents);

        return $configuration;
    }
}

