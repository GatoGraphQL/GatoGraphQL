<?php
class PoP_ServerSide_ResourceLoader
{
    public $config;

    public function __construct()
    {
        PoP_ResourceLoader_ServerSide_LibrariesFactory::setResourceloaderInstance($this);
        
        // Initialize internal variables
        $this->config = array();
    }

    //-------------------------------------------------
    // PUBLIC but NOT EXPOSED functions
    //-------------------------------------------------

    protected function includeResource(array $resource)
    {

        // Include the script/style link
        if (PoP_ResourceLoader_ServerUtils::includeResourcesInBody()) {
            global $pop_resourceloaderprocessor_manager;
            $config = $this->config[$this->domain];

            // $resource_id = PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resource);
            $resource_id = $pop_resourceloaderprocessor_manager->getHandle($resource);
            $include_type = PoP_ResourceLoader_ServerUtils::getResourcesIncludeType();

            // For both 'body' and 'body-inline', include the style/script file when the pageSectionPage is destroyed
            $resourceFullName = ResourceUtils::getResourceFullName($resource);
            $source = $config['sources'][$resourceFullName];
            if ($include_type == 'body') {
                // If destroying the pageSectionPage, the corresponding 'in-body' styles will also be deleted, and other pages using those styles will be affected.
                // Then, simply load again those removed resources (scripts and styles)
                if (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_CSS])) {
                    $tag = sprintf(
                        '<link id="%s" rel="stylesheet" href="%s">',
                        $resource_id,
                        $source
                    );
                    return $tag;
                }
                // else if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                elseif (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_JS])) {
                    $tag = sprintf(
                        '<script id="%s" type="text/javascript" src="%s"></script>',
                        $resource_id,
                        $source
                    );
                    return $tag;
                }
            }
            // Include the content of the file
            elseif ($include_type == 'body-inline') {
                global $pop_resourceloaderprocessor_manager;
                $file = $pop_resourceloaderprocessor_manager->getFilePath($resource);
                $file_contents = file_get_contents($file);

                if (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_CSS])) {
                    $inline = sprintf(
                        '<style id="%s" type="text/css">%s</style>',
                        $resource_id,
                        $file_contents
                    );
                    return $inline;
                } elseif (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_JS])) {
                    $inline = sprintf(
                        '<script id="%s" type="text/javascript">%s</script>',
                        $resource_id,
                        $file_contents
                    );
                    return $inline;
                }
            }
        }

        return '';
    }

    public function includeResources($domain, $blockId, $resources)
    {

        // Map the resources to their tags. First set the domain so it can be accessed in that function
        $this->domain = $domain;
        $tags = array_map($this->includeResource(...), $resources);

        return implode('', $tags);
    }
}

/**
 * Initialization
 */
new PoP_ServerSide_ResourceLoader();
