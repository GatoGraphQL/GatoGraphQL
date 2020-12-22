<?php
use PoP\FileStore\File\AbstractFile;

class PoP_ResourceLoader_BundleFile_FileRenderer extends \PoP\FileStore\Renderer\FileRenderer
{
    public function renderAndSave(AbstractFile $file): void
    {
        // Insert the current file URL to the generated resources file
        foreach ($file->getFragments() as $fragment) {

            // Set all the resources on the fileReproduction, so it can retrieve their contents
            $fragment->setResources($file->getResources());
        }

        parent::renderAndSave($file);

        // Copy the depended-upon files, such as fonts referenced inside CSS files
        self::copyReferencedFiles($file);
    }

    protected static function copyReferencedFiles(AbstractFile $file)
    {
        // Reset
        $file->resetGeneratedReferencedFiles();

        // Copy the dependencies, such as fonts referenced inside CSS files
        global $pop_resourceloaderprocessor_manager;
        $destination_dir = $file->getDir();
        foreach ($file->getResources() as $resource) {
            $processor = $pop_resourceloaderprocessor_manager->getProcessor($resource);
            if ($resource_referenced_files = $processor->getReferencedFiles($resource)) {
                $source_dir = $processor->getDir($resource);
                foreach ($resource_referenced_files as $relative_path_to_referenced_file) {

                    // Check that the file does not exist yet
                    $destination_file = getAbsolutePath($destination_dir.'/'.$relative_path_to_referenced_file);
                    // if (!file_exists($destination_file)) {

                    $source_file = getAbsolutePath($source_dir.'/'.$relative_path_to_referenced_file);
                    
                    // Copy only works if the destination folder exists
                    @mkdir(dirname($destination_file), 0777, true);
                    copy($source_file, $destination_file);
                    // }

                    $file->addGeneratedReferencedFile($destination_file);
                }
            }
        }
    }
}
