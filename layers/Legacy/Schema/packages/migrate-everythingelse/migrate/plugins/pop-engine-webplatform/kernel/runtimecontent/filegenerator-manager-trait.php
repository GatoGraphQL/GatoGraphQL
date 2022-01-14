<?php
use PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade;
use PoP\Root\App;

trait FileGeneratorManagerTrait
{
    use \PoP\ComponentModel\Cache\ReplaceCurrentExecutionDataWithPlaceholdersTrait;

    protected function addDeleteFilesHooks()
    {
        // When a plugin is activated/deactivated, ANY plugin, delete the corresponding cached files
        // This is particularly important for the MEMORY, since we can't set by constants to not use it
        // These are WP hooks, must migrate them to PoP hooks
        App::addAction('activate_plugin', [$this, 'deleteFiles']);
        App::addAction('deactivate_plugin', [$this, 'deleteFiles']);
    }

    public function deleteFiles()
    {
        if (file_exists($this->getFileBasedir())) {
            deleteDir($this->getFileBasedir());
        }
    }

    protected function getFileBasedir()
    {
        return '';
    }

    protected function getFileDir($type)
    {
        return $this->getFileBasedir().'/'.$type;
    }

    public function getFile($filename, $type, $ext = '')
    {
        if (!$ext) {
            $ext = $this->getDefaultExtension();
        }

        return $this->getFileDir($type).'/'.$filename.$ext;
    }

    public function fileExists($filename, $type, $ext = '')
    {
        $file = $this->getFile($filename, $type, $ext);
        return file_exists($file);
    }

    private function saveFile($type, $file, $contents)
    {

        // Replace the uniqueId with the placeholder to keep the saved settings uniqueId-independent
        // Comment Leo 06/03/2017: do the same with all dynamic constants, so that we can generate a proper ETag also when retrieving the cached value
        if ($replacements = $this->getSaveFileCacheReplacements()) {
            $from = $replacements['from'];
            $to = $replacements['to'];
            if ($from && $to) {
                $contents = str_replace($from, $to, $contents);
            }
        }

        // Make sure the directory exists
        $dir = $this->getFileDir($type);
        if (!file_exists($dir)) {
            // Create the settings folder
            @mkdir($dir, 0777, true);
        }

        // Open the file, write content and close it
        $handle = fopen($file, "wb");
        
        // Possibly because the $contents are an encoded JSON, the line below produces an error, so commented the PHP_EOL bit
        // $numbytes = fwrite($handle, implode(PHP_EOL, $contents));
        $numbytes = fwrite($handle, $contents);
        fclose($handle);

        return $file;
    }

    protected function getDefaultExtension()
    {
        return '';
    }

    public function getFileWithModelInstanceFilename($type, $ext = '')
    {
        $model_instance_id = ModelInstanceFacade::getInstance()->getModelInstanceId();
        return $this->getFile($model_instance_id, $type, $ext);
    }

    public function fileWithModelInstanceFilenameExists($type, $ext = '')
    {
        $model_instance_id = ModelInstanceFacade::getInstance()->getModelInstanceId();
        return $this->fileExists($model_instance_id, $type, $ext);
    }

    public function generateFileWithModelInstanceFilename($type, $content, $ext = '')
    {
        $file = $this->getFileWithModelInstanceFilename($type, $ext);
        return $this->saveFile($type, $file, $content);
    }
}
