<?php
class PoP_SPAResourceLoader_ConfigNatureFormatCombinationResourcesFile extends PoP_SPAResourceLoader_ConfigAddResourcesFileBase
{
    protected $current_nature;
    protected $current_format;

    public function getDir(): string
    {
        return parent::getDir().'/partials';
    }
    public function getUrl(): string
    {
        return parent::getUrl().'/partials';
    }

    // // Generate multiple config files (one for each combination of nature and format) instead of just one
    // public function generate()
    // {
    //     $renderer = $this->getRenderer();
    //     $renderer_filereproductions = $renderer->get();

    //     // Obtain the list of formats configured under every nature
    //     foreach (PoP_ResourceLoaderProcessorUtils::getNatureRoutesAndFormats() as $nature => $route_formats) {

    //         // Assign the nature and format to both the generator, for the filename,
    //         // and to the filereproductions, to generate the configuration
    //         $this->current_nature = $nature;
    //         foreach ($renderer_filereproductions as $filereproduction) {
    //             $filereproduction->setNature($nature);
    //         }

    //         // $route_formats is array of ($route => array($format))
    //         $formats = array_unique(arrayFlatten(array_values($route_formats)));
    //         foreach ($formats as $format) {
    //             $this->current_format = $format;
    //             foreach ($renderer_filereproductions as $filereproduction) {
    //                 $filereproduction->setFormat($format);
    //             }

    //             // Finally, given this combination of nature and format, call the parent generate function
    //             parent::generate();
    //         }
    //     }
    // }

    public function getFilename(): string
    {
        return $this->getVariableFilename($this->current_nature, $this->current_format);
    }

    public function getVariableFilename($nature, $format)
    {
        return sprintf(
            'config-resources-%s%s.js',
            $nature,
            $format
        );
    }

    // public function getRenderer()
    // {
    //     global $pop_sparesourceloader_natureformatcombinationresources_configfile_renderer;
    //     return $pop_sparesourceloader_natureformatcombinationresources_configfile_renderer;
    // }
    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
    {
        return [
            new PoP_SPAResourceLoader_FileReproduction_NatureFormatCombinationResourcesConfig(),
        ];
    }
}

/**
 * Initialize
 */
global $pop_sparesourceloader_natureformatcombinationresources_configfile;
$pop_sparesourceloader_natureformatcombinationresources_configfile = new PoP_SPAResourceLoader_ConfigNatureFormatCombinationResourcesFile();
