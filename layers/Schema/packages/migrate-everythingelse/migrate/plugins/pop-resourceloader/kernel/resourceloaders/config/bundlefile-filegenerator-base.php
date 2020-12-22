<?php
abstract class PoP_ResourceLoader_BundleFileFileBase extends PoP_ResourceLoader_ResourcesFileBase
{
    protected $filename;
    protected $resources;
    protected $generated_referenced_files;

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
    public function getFilename(): string
    {
        return $this->filename;
    }
    public function getGeneratedReferencedFiles()
    {
        return $this->generated_referenced_files;
    }
    public function resetGeneratedReferencedFiles()
    {
        $this->generated_referenced_files = [];
    }
    public function addGeneratedReferencedFile($file)
    {
        $this->generated_referenced_files[] = $file;
    }

    public function setResources($resources)
    {

        // Only the resources that can be bundled
        global $pop_resourceloaderprocessor_manager;
        $this->resources = $pop_resourceloaderprocessor_manager->filterCanBundle($resources);
    }

    public function getResources()
    {
        return $this->resources ?? array();
    }

    // public function generate()
    // {

    //     // Insert the current file URL to the generated resources file
    //     $renderer = $this->getRenderer();
    //     $renderer_filereproductions = $renderer->get();

    //     foreach ($renderer_filereproductions as $filereproduction) {

    //         // Set all the resources on the fileReproduction, so it can retrieve their contents
    //         $filereproduction->setResources($this->getResources());
    //     }

    //     parent::generate();

    //     // Copy the depended-upon files, such as fonts referenced inside CSS files
    //     $this->copyReferencedFiles();
    // }

    // protected function copyReferencedFiles()
    // {

    //     // Reset
    //     $this->generated_referenced_files = array();

    //     // Copy the dependencies, such as fonts referenced inside CSS files
    //     global $pop_resourceloaderprocessor_manager;
    //     $destination_dir = $this->getDir();
    //     foreach ($this->getResources() as $resource) {
    //         $processor = $pop_resourceloaderprocessor_manager->getProcessor($resource);
    //         if ($resource_referenced_files = $processor->getReferencedFiles($resource)) {
    //             $source_dir = $processor->getDir($resource);
    //             foreach ($resource_referenced_files as $relative_path_to_referenced_file) {

    //                 // Check that the file does not exist yet
    //                 $destination_file = getAbsolutePath($destination_dir.'/'.$relative_path_to_referenced_file);
    //                 // if (!file_exists($destination_file)) {

    //                 $source_file = getAbsolutePath($source_dir.'/'.$relative_path_to_referenced_file);

    //                 // Copy only works if the destination folder exists
    //                 @mkdir(dirname($destination_file), 0777, true);
    //                 copy($source_file, $destination_file);
    //                 // }

    //                 $this->generated_referenced_files[] = $destination_file;
    //             }
    //         }
    //     }
    // }

    // public function getRenderer()
    // {
    //     global $pop_resourceloader_mirrorcode_renderer;
    //     return $pop_resourceloader_mirrorcode_renderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoP_ResourceLoader_FileReproduction_ResourcesMirrorCode(),
        ];
    }
}
