<?php
namespace PoP\Engine\FileStorage;

class FileRenderer
{
    public $files;
    
    public function __construct()
    {
        $this->files = array();
    }
    
    public function add($file)
    {
        $this->files[] = $file;
    }
    
    public function get()
    {
        return $this->files;
    }

    public function render()
    {
        $parts = array();
        
        foreach ($this->files as $file) {
            $parts[] = $this->renderFile($file->getAssetsPath(), $file->getConfiguration(), $file->isJsonReplacement(), $file->getJsonencodeOptions());
        }
        return implode(PHP_EOL, $parts);
    }

    private function renderFile($path, $replacements, $isJsonReplacement, $jsonencode_options = 0)
    {
        $contents = file_get_contents($path);
        foreach ($replacements as $key => $replacement) {
            $value = $isJsonReplacement ? json_encode($replacement, $jsonencode_options) : $replacement;
            $contents = str_replace($key, $value, $contents);
        }
        return $contents;
    }
}
