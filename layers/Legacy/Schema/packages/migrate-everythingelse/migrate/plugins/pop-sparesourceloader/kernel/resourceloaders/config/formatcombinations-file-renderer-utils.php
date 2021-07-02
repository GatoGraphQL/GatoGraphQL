<?php
use PoP\FileStore\File\AbstractFile;

class PoP_SPAResourceLoader_ConfigNatureFormatCombinationResources_FileRenderer extends PoP_SPAResourceLoader_ConfigAddResources_FileRenderer
{
    public function renderAndSave(AbstractFile $file): void
    {
        // Generate multiple config files (one for each combination of nature and format) instead of just one
        $fragments = $file->getFragments();

        // Obtain the list of formats configured under every nature
        foreach (PoP_ResourceLoaderProcessorUtils::getNatureRoutesAndFormats() as $nature => $route_formats) {

            // Assign the nature and format to both the generator, for the filename,
            // and to the filereproductions, to generate the configuration
            $file->current_nature = $nature;
            foreach ($fragments as $fragment) {
                $fragment->setNature($nature);
            }
        
            // $route_formats is array of ($route => array($format))
            $formats = array_unique(arrayFlatten(array_values($route_formats)));
            foreach ($formats as $format) {
                $file->current_format = $format;
                foreach ($fragments as $fragment) {
                    $fragment->setFormat($format);
                }

                // Finally, given this combination of nature and format, call the parent generate function
                parent::renderAndSave($file);
            }
        }
    }
}
