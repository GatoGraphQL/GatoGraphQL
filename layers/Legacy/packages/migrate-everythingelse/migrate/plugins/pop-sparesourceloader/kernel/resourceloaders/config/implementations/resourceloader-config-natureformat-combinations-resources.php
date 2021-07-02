<?php

class PoP_SPAResourceLoader_FileReproduction_NatureFormatCombinationResourcesConfig extends PoP_SPAResourceLoader_FileReproduction_AddResourcesConfigBase
{
    protected $nature;
    protected $format;
    protected $fileurl;

    // public function getRenderer()
    // {
    //     global $pop_sparesourceloader_natureformatcombinationresources_configfile_renderer;
    //     return $pop_sparesourceloader_natureformatcombinationresources_configfile_renderer;
    // }

    public function setNature($nature)
    {
        return $this->nature = $nature;
    }

    public function setFormat($format)
    {
        return $this->format = $format;
    }

    protected function matchNature()
    {
        return $this->nature;
    }

    protected function matchFormat()
    {
        return $this->format;
    }
}
   