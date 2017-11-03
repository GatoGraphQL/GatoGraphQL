<?php
class PoP_Engine_FileRenderer {

    var $files;
    
    function __construct() {
    
		$this->files = array();
	}
	
    function add($file) {
    
		$this->files[] = $file;		
	}
	
	function get() {

		return $this->files;
	}

    public function render() {
        
        $parts = array();
        
		foreach ($this->files as $file) {

			$parts[] = $this->render_file($file->get_js_path(), $file->get_configuration(), $file->get_jsonencode_options());
		}
		return implode(/*';'*/PHP_EOL, $parts);
    }

    private function render_file($path, $replacements, $jsonencode_options = 0) {

        $contents = file_get_contents($path);
        foreach ($replacements as $key => $replacement) {
            $value = json_encode($replacement, $jsonencode_options);
            $contents = str_replace($key, $value, $contents);
        }
        return $contents;
    }
}
