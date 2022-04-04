<?php
class PoP_ServerSide_SPAResourceLoader
{

    // The values here will be populated from resourceloader-config.js,
    // on a domain by domain basis
    public $config;
    public $blockId;

    // Keep a list of all loading resources
    // private $loading;
    // private $errorLoading;
    // private $loadingURLs;

    // Keep a list of all loaded resources. All resources are called always the same among different domains,
    // so one list here listing all of them works
    public $loaded;
    public $loadedInBody;
    // Loaded bundles and bundleGroups depend on their domains, since their names change among domains
    // private $loadedByDomain;

    public function __construct()
    {
        PoP_ResourceLoader_ServerSide_LibrariesFactory::setResourceloaderInstance($this);
        
        // Initialize internal variables
        $this->config = array();
        // $this->loading = array(
        //     'resources' => array(),
        // );
        // $this->errorLoading = array(
        //     'resources' => array(),
        // );
        // $this->loadingURLs = array();
        $this->loaded = array();
        $this->loadedInBody = array();
        // $this->loadedByDomain = array();
    }

    //-------------------------------------------------
    // PUBLIC but NOT EXPOSED functions
    //-------------------------------------------------

    protected function includeResource(array $resource)
    {

        // Include the script/style link
        if (PoP_ResourceLoader_ServerUtils::includeResourcesInBody()) {
            global $pop_sparesourceloaderprocessor_manager;
            $config = $this->getConfigByDomain($this->domain);
            $blockId = $this->blockId;
            // $resource_id = PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resource);
            $resource_id = $pop_sparesourceloaderprocessor_manager->getHandle($resource);
            $include_type = PoP_ResourceLoader_ServerUtils::getResourcesIncludeType();

            // For both 'body' and 'body-inline', include the style/script file when the pageSectionPage is destroyed
            $script = '';
            $resourceFullName = ResourceUtils::getResourceFullName($resource);
            $source = $config['sources'][$resourceFullName];
            $fn = '<script type="text/javascript">jQuery(document).ready( function($) { popSPAResourceLoader.onRemoveLoadResource("%s", "%s", "%s"); });</script>';
            if (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_CSS])) {
                $script = sprintf(
                    $fn,
                    $blockId,
                    POP_RESOURCELOADER_RESOURCETYPE_CSS,
                    $source
                );
            } elseif (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_JS])) {
                $script = sprintf(
                    $fn,
                    $blockId,
                    POP_RESOURCELOADER_RESOURCETYPE_JS,
                    $source
                );
            }

            if ($include_type == 'body') {
                // If destroying the pageSectionPage, the corresponding 'in-body' styles will also be deleted, and other pages using those styles will be affected.
                // Then, simply load again those removed resources (scripts and styles)
                if (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_CSS])) {
                    $tag = sprintf(
                        '<link id="%s" rel="stylesheet" href="%s">',
                        $resource_id,
                        $source
                    );
                    return $script.$tag;
                }
                // else if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                elseif (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_JS])) {
                    $tag = sprintf(
                        '<script id="%s" type="text/javascript" src="%s"></script>',
                        $resource_id,
                        $source
                    );
                    return $script.$tag;
                }
            }
            // Include the content of the file
            elseif ($include_type == 'body-inline') {
                global $pop_sparesourceloaderprocessor_manager;
                $file = $pop_sparesourceloaderprocessor_manager->getFilePath($resource);
                $file_contents = file_get_contents($file);

                if (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_CSS])) {
                    $inline = sprintf(
                        '<style id="%s" type="text/css">%s</style>',
                        $resource_id,
                        $file_contents
                    );
                    return $script.$inline;
                }
                // else if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                elseif (in_array($resourceFullName, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_JS])) {
                    $inline = sprintf(
                        '<script id="%s" type="text/javascript">%s</script>',
                        $resource_id,
                        $file_contents
                    );
                    return $script.$inline;
                }
            }
        }

        return '';
    }

    public function includeResources($domain, $blockId, $resources, $ignoreAlreadyIncluded)
    {
        if (!$resources) {
            return '';
        }

        // Only

        $body_resources = array();

        // Remove the resources that have been included already
        if ($ignoreAlreadyIncluded) {
            // Comment Leo 23/11/2017: if a component is lazy-loaded, and inside has a CSS file that is printed in the body,
            // then we must check if that resource has been added to the body. (It will be already marked as "loaded" by the website,
            // but it never was actually because of the lazy-loading)
            $body_resources = array_diff(
                $resources,
                $this->loadedInBody
            );

            $resources = array_diff(
                $resources,
                $this->loaded
            );
        }

        // Mark the resources as already included
        $this->loaded = array_merge(
            $this->loaded,
            $resources
        );
        
        $this->loadedInBody = array_merge(
            $this->loadedInBody,
            $body_resources
        );
        $resources = $body_resources;

        // Map the resources to their tags. First set the domain so it can be accessed in that function
        $this->domain = $domain;
        $this->blockId = $blockId;
        $tags = array_map($this->includeResource(...), $resources);

        return implode('', $tags);
    }

    public function getConfigByDomain($domain)
    {
        return $this->config[$domain];
    }
}

/**
 * Initialization
 */
new PoP_ServerSide_SPAResourceLoader();
