<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_WebPlatformEngineOptimizations_EngineInitialization_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoPWebPlatform_Engine:encoded-data-object',
            array($this, 'getEncodedDataObject'),
            10,
            2
        );
    }

    public function getEncodedDataObject($data, $processor)
    {

        // Optimizations to be made when first loading the website
        if (RequestUtils::loadingSite()) {
            // Do not send the settings to be output as code.
            // Instead, save the settings contents into a javascript file, and enqueue it
            if (PoP_WebPlatformEngineOptimizations_ServerUtils::extractResponseIntoJsfilesOnRuntime()) {
                // Settings
                $this->optimizeEncodedData($data, $processor, array('modulesettings'), POP_RUNTIMECONTENTTYPE_SETTINGS, true);
            }
        }
            
        return $data;
    }

    public function optimizeEncodedData(&$data, $processor, $property_path, $type, $do_replacements)
    {
        $value = $data;
        foreach ($property_path as $path) {
            $value = $value[$path];
        }
        if ($value) {
            $value_js = '';

            // Initialize variable pop.InitializeData.extensions to an empty object for the multiple path levels before the last one
            $previous_path = '';
            $i = 0;
            while ($i < count($property_path)-1) {
                $path = $property_path[$i];
                $current_path = '[\''.$path.'\']';
                $value_js .= sprintf(
                    'pop.InitializeData.extensions%1$s%2$s = pop.InitializeData.extensions%1$s%2$s || {};',
                    $previous_path,
                    $current_path
                );
                $previous_path .= $current_path;
                $i++;
            }

            // Assign the value to the corresponding property
            $js_var = sprintf(
                '%s%s',
                'pop.InitializeData.extensions',
                '[\''.implode('\'][\'', $property_path).'\']'
            );
            
            // Check if this file has already been generated. If not, then save it
            global $pop_module_runtimecontentmanager;
            $module = $processor->getEntryModule();
            if (!$pop_module_runtimecontentmanager->fileWithModelInstanceFilenameExists($type)) {
                   // Generate and save the JS code
                $placeholder = '%1$s = %2$s;';
                if ($do_replacements) {
                    $value_js .= sprintf(
                        $placeholder,
                        $js_var,
                        // Encoded twice: the first one for the array, the 2nd one to convert it to string
                        json_encode(json_encode($value))
                    );
                } else {
                    $value_js .= sprintf(
                        $placeholder,
                        $js_var,
                        json_encode($value)
                    );
                }
                $pop_module_runtimecontentmanager->generateFileWithModelInstanceFilename($type, $value_js);

                // In addition, this file must be uploaded to AWS S3 bucket, so that this scheme of generating the file on runtime
                // can also work when hosting the website at multiple servers
                HooksAPIFacade::getInstance()->doAction(
                    '\PoP\ComponentModel\Engine:optimizeEncodedData:file_stored',
                    $module,
                    $property_path,
                    $type
                );
            }

            // Enqueue the .js file
            if ($file_url = $pop_module_runtimecontentmanager->getFileUrlByModelInstance($type)) {
                // Comment Leo 07/11/2017: if enqueuing bundle/bundlegroup scripts, then we don't have the pop-manager.js file enqueued,
                // Then, move the functionality below to `enqueueScripts`, as to wait until the corresponding scripts have been enqueued
                // Comment Leo 06/01/2018: We need to add 'crossorigin="anonymous"' because the file will be uploaded to S3, and when accessing this file
                // we will need header Access-Control-Allow-Origin "*", and through the S3 bucket's CORS configuration, we will get that header
                // only if there is a request header "Origin". Adding the crossorigin will add header "Origin: null" which does the job
                // Taken from https://stackoverflow.com/questions/17533888/s3-access-control-allow-origin-header#answer-35278803
                $processor->addEnqueueItem(
                    array(
                        'property' => implode('-', $property_path),
                        'file-url' => $file_url,
                        'scripttag-attributes' => 'crossorigin="anonymous"',
                    )
                );
            }

            // We must also do the replacements on the JS code (getLoadFileCacheReplacements)
            if ($do_replacements) {
                if ($replacements = $pop_module_runtimecontentmanager->getLoadFileCacheReplacements()) {
                    $from = $replacements['from'];
                    $to = $replacements['to'];
                    if ($from && $to) {
                        $replaces = '';
                        for ($i=0; $i<count($from); $i++) {
                            $replaces .= sprintf(
                                '.replace(/%s/g, \'%s\')',
                                $from[$i],
                                $to[$i]
                            );
                        }
                        $processor->addScriptsItem(
                            sprintf(
                                'if (%1$s) {'.
                                '%1$s = JSON.parse(%1$s%2$s);'.
                                '}',
                                $js_var,
                                $replaces
                            )
                        );
                    }
                }
            }

            // Remove the entry from the html output. Because $property_path is an array, we must iterate the array to that entry
            $value = &$data;
            $last_path = array_pop($property_path);
            foreach ($property_path as $path) {
                $value = &$value[$path];
            }
            unset($value[$last_path]);
        }
    }
}

/**
 * Initialization
 */
new PoP_WebPlatformEngineOptimizations_EngineInitialization_Hooks();
