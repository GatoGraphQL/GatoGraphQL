<?php
namespace PoP\Engine\FileStorage;

class FileJSONStorage extends FileStorageBase
{
    public function __construct()
    {
        FileJSONStorage_Factory::setInstance($this);
    }

    public function save($file, $contents)
    {

        // Encode it and save it
        $json = json_encode($contents);
        $this->saveFile($file, $json);
    }

    public function get($file)
    {
        if (file_exists($file)) {
            // Return the file contents
            $contents = file_get_contents($file);
            return json_decode($contents, true);
        }

        return array();
    }

    public function delete($file)
    {
        if (file_exists($file)) {
            unlink($file);
            return true;
        }

        return false;
    }
}
    
/**
 * Initialize
 */
new FileJSONStorage();
