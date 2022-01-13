<?php
use PoP\FileStore\Facades\FileRendererFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/**
 * Change styles according to the domain
 */
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_DomainCodes:code:styles', 'getMultidomainBgcolorCodestyle', 10, 2);
function getMultidomainBgcolorCodestyle($styles, $domain)
{
	// Use an anonymous class, since this file will never need be saved to disk
	$file = (new class() extends \PoP\FileStore\File\AbstractAccessibleRenderableFile
		{
		    public function getDir(): string
		    {
		        return '';
		    }
		    public function getUrl(): string
		    {
		        return '';
		    }
		    public function getFilename(): string
		    {
		        return '';
		    }
		    /**
     * @return AbstractRenderableFileFragment[]
     */
    protected function getFragmentObjects(): array
		    {
		        return [
		            new PoPTheme_Wassup_Multidomain_FileReproduction_Styles(),
		        ];
		    }
		});
    foreach ($file->getFragments() as $fragment) {
        $fragment->setDomain($domain);
    }
    $styles .= FileRendererFacade::getInstance()->render($file);
    return $styles;
}
