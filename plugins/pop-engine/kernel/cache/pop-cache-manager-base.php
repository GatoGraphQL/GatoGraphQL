<?php
namespace PoP\Engine;

class CacheManagerBase
{
    public function __construct()
    {

        // When a plugin is activated/deactivated, ANY plugin, delete the corresponding cached files
        // This is particularly important for the MEMORY, since we can't set by constants to not use it
        add_action(
            'activate_plugin',
            array($this, 'deleteCache')
        );
        add_action(
            'deactivate_plugin',
            array($this, 'deleteCache')
        );
    }

    public function deleteCache()
    {
        if (file_exists($this->getCacheBasedir())) {
            deleteDir($this->getCacheBasedir());
        }
    }

    protected function getCacheBasedir()
    {
        return '';
    }

    protected function getCacheDir($type)
    {
        return $this->getCacheBasedir().'/'.$type;
    }

    protected function getDefaultExtension()
    {
        return POP_CACHE_EXT_JSON;
    }

    public function getFileByModelInstance($type, $ext = '')
    {
        $model_instance_id = ModelInstanceProcessor_Utils::getModelInstanceId();
        return $this->getFile($model_instance_id, $type, $ext);
    }

    public function getFile($filename, $type, $ext = '')
    {

        // $filename = $this->getFilename($filename);

        if (!$ext) {
            $ext = $this->getDefaultExtension();
        }

        return $this->getCacheDir($type).'/'.$filename.$ext;
    }

    // private function getFilename($model_instance_id) {

    //     // Do not start with a number (just in case)
    //     return 'c'.$model_instance_id;
    // }
    private function getFilename($filename)
    {
        return $filename;
    }

    public function createCacheDir($type)
    {
        $cache_dir = $this->getCacheDir($type);
        if (!file_exists($cache_dir)) {
            // Create the settings folder
            @mkdir($cache_dir, 0777, true);
        }
    }

    public function getSavefileCacheReplacements()
    {
        return array(
            'from' => array(
                POP_CONSTANT_UNIQUE_ID,
                POP_CONSTANT_CURRENTTIMESTAMP,
                POP_CONSTANT_RAND,
                POP_CONSTANT_TIME,
            ),
            'to' => array(
                POP_CACHEPLACEHOLDER_UNIQUE_ID,
                POP_CACHEPLACEHOLDER_CURRENTTIMESTAMP,
                POP_CACHEPLACEHOLDER_RAND,
                POP_CACHEPLACEHOLDER_TIME,
            ),
        );
    }

    public function getLoadfileCacheReplacements()
    {
        $savefile_replacements = $this->getSavefileCacheReplacements();
        return array(
            'from' => $savefile_replacements['to'],
            'to' => $savefile_replacements['from'],
        );
    }

    public function cacheExistsByModelInstance($type, $ext = '')
    {
        $model_instance_id = ModelInstanceProcessor_Utils::getModelInstanceId();
        return $this->cacheExists($model_instance_id, $type, $ext);
    }

    public function cacheExists($filename, $type, $ext = '')
    {
        $file = $this->getFile($filename, $type, $ext);
        return file_exists($file);
    }

    public function getCacheByModelInstance($type, $decode = false, $ext = '')
    {
        $model_instance_id = ModelInstanceProcessor_Utils::getModelInstanceId();
        return $this->getCache($model_instance_id, $type, $decode, $ext);
    }

    public function getCache($filename, $type, $decode = false, $ext = '')
    {
        $file = $this->getFile($filename, $type, $ext);
        if (file_exists($file)) {
            // Return the file contents
            $contents = file_get_contents($file);

            // Replace the placeholder for the uniqueId with the current uniqueId
            // Comment Leo 06/03/2017: do the same with all dynamic constants, so that we can generate a proper ETag also when retrieving the cached value
            if ($replacements = $this->getLoadfileCacheReplacements()) {
                $from = $replacements['from'];
                $to = $replacements['to'];
                if ($from && $to) {
                    $contents = str_replace($from, $to, $contents);
                }
            }

            if ($decode) {
                // Treat it as an array, not an object
                $contents_or_object = json_decode($contents, true);
            } else {
                $contents_or_object = $contents;
            }

            return $contents_or_object;
        }

        return false;
    }

    public function storeCacheByModelInstance($type, $contents_or_object, $encode = false, $ext = '')
    {
        $model_instance_id = ModelInstanceProcessor_Utils::getModelInstanceId();
        return $this->storeCache($model_instance_id, $type, $contents_or_object, $encode, $ext);
    }

    public function storeCache($filename, $type, $contents_or_object, $encode = false, $ext = '')
    {
        $file = $this->getFile($filename, $type, $ext);

        if ($encode) {
            $contents = json_encode($contents_or_object);
        } else {
            $contents = $contents_or_object;
        }
        
        return $this->saveFile($type, $file, $contents);
    }

    private function saveFile($type, $file, $contents)
    {

        // Replace the uniqueId with the placeholder to keep the saved settings uniqueId-independent
        // Comment Leo 06/03/2017: do the same with all dynamic constants, so that we can generate a proper ETag also when retrieving the cached value
        if ($replacements = $this->getSavefileCacheReplacements()) {
            $from = $replacements['from'];
            $to = $replacements['to'];
            if ($from && $to) {
                $contents = str_replace($from, $to, $contents);
            }
        }

        // Make sure the directory exists
        $this->createCacheDir($type);

        // Open the file, write content and close it
        $handle = fopen($file, "wb");
        
        // Possibly because the $contents are an encoded JSON, the line below produces an error, so commented the PHP_EOL bit
        // $numbytes = fwrite($handle, implode(PHP_EOL, $contents));
        $numbytes = fwrite($handle, $contents);
        fclose($handle);

        return $file;
    }
}
