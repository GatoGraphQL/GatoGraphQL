<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

class PoP_ServiceWorkersManager
{
    private $scope;

    public function __construct()
    {
        $this->scope = site_url('/', 'relative');
    }

    public function getDir(): string
    {
        return POP_SERVICEWORKERS_ASSETDESTINATION_DIR;
    }
    public function getUrl(): string
    {
        return POP_SERVICEWORKERS_ASSETDESTINATION_URL;
    }
    public function getDependenciesFoldername()
    {
        return 'lib';
    }

    public function getFilepath($filename)
    {
        return $this->getDir().'/'.$filename;
    }

    public function getFileurl($filename)
    {
        return $this->getUrl().'/'.$filename;
    }

    private function swRegistrar()
    {
        $contents = file_get_contents(POP_SERVICEWORKERS_ASSETS_DIR.'/js/sw-registrar.js');
        $contents = str_replace('$enabledSw', json_encode($this->jsonForSwRegistrations()), $contents);
        return $contents;
    }

    private function manifest()
    {
        $json = array();

		$cmsService = CMSServiceFacade::getInstance();
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $blogname = $cmsapplicationapi->getSiteName();
        $description = $cmsapplicationapi->getSiteDescription();
        if ($short_name = \PoP\Root\App::applyFilters('PoP_ServiceWorkersManager:manifest:short_name', $blogname)) {
            $json['short_name'] = $short_name;
        }
        if ($description = \PoP\Root\App::applyFilters('PoP_ServiceWorkersManager:manifest:description', $description)) {
            $json['description'] = $description;
        }
        if ($name = \PoP\Root\App::applyFilters('PoP_ServiceWorkersManager:manifest:name', $blogname)) {
            $json['name'] = $name;
        }
        if ($icons = \PoP\Root\App::applyFilters('PoP_ServiceWorkersManager:manifest:icons', array())) {
            $json['icons'] = $icons;
        }
        if ($start_url = \PoP\Root\App::applyFilters('PoP_ServiceWorkersManager:manifest:start_url', $cmsService->getSiteURL())) {
            $json['start_url'] = $start_url;
        }
        if ($display = \PoP\Root\App::applyFilters('PoP_ServiceWorkersManager:manifest:display', 'standalone')) {
            $json['display'] = $display;
        }
        if ($orientation = \PoP\Root\App::applyFilters('PoP_ServiceWorkersManager:manifest:orientation', 'portrait')) {
            $json['orientation'] = $orientation;
        }
        if ($theme_color = \PoP\Root\App::applyFilters('PoP_ServiceWorkersManager:manifest:theme_color', '#fff')) {
            $json['theme_color'] = $theme_color;
        }
        if ($background_color = \PoP\Root\App::applyFilters('PoP_ServiceWorkersManager:manifest:background_color', '#fff')) {
            $json['background_color'] = $background_color;
        }

        return $json;

        // $contents = file_get_contents(POP_SERVICEWORKERS_ASSETS_DIR.'/js/manifest.json');
        //       $contents = str_replace($configuration, $values, $contents);
        //       return $contents;
    }

    private function htaccess()
    {
        $contents = file_get_contents(POP_SERVICEWORKERS_ASSETS_DIR.'/htaccess');
        $contents = str_replace('$scope', $this->scope, $contents);
        return $contents;
    }

    private function jsonForSwRegistrations()
    {
        return array(
            array(
                'scope' => $this->scope,
                'url' => $this->getFileurl('service-worker.js')
            )
        );
    }

    public function generateFiles()
    {
        global $pop_serviceworkers_job_manager;

        // Create the directory structure
        $this->createDir();

        // File to register the Service Worker
        $this->saveFile($this->getFilepath('sw-registrar.js'), $this->swRegistrar());

        // Service Worker .js file
        $sw_contents = $pop_serviceworkers_job_manager->renderSw($this->scope);
        $this->saveFile($this->getFilepath('service-worker.js'), $sw_contents);

        // Manifest.json
        $this->saveFile($this->getFilepath('manifest.json'), json_encode($this->manifest(), JSON_UNESCAPED_SLASHES));

        // Copy the dependencies
        $dependencies = $pop_serviceworkers_job_manager->getDependencies($this->scope);
        foreach ($dependencies as $dependency) {
            copy($dependency, $this->getFilepath($this->getDependenciesFoldername().'/'.basename($dependency)));
        }

        // Create the .htaccess file to allow access to the scope (/) served by a file in another folder
        $this->saveFile($this->getFilepath('.htaccess'), $this->htaccess());
        // copy(POP_SERVICEWORKERS_ASSETS_DIR.'/.htaccess', $this->getFilepath('.htaccess'));
    }

    private function saveFile($file, $contents)
    {

        // Open the file, write content and close it
        $handle = fopen($file, "wb");

        // Possibly because the $contents are an encoded JSON, the line below produces an error, so commented the PHP_EOL bit
        // $numbytes = fwrite($handle, implode(PHP_EOL, $contents));
        $numbytes = fwrite($handle, $contents);
        fclose($handle);

        return $file;
    }

    private function createDir()
    {
        $dir = $this->getDir();
        if (!file_exists($dir)) {
            // Create folder
            @mkdir($dir, 0777, true);
        }
        $dir .= '/'.$this->getDependenciesFoldername();
        if (!file_exists($dir)) {
            // Also the dependencies folder
            @mkdir($dir, 0777, true);
        }
        // else {

        //     // Delete all .js files
        //     foreach(glob("{$dir}/*") as $file) {
        //         if (is_file($file)) {
        //             unlink($file);
        //         }
        //     }
        // }

        // // Copy the .htaccess file, which will be needed for service-worker.js
        // copy(POP_SERVICEWORKERS_ASSETS_DIR.'/.htaccess', $this->getFilepath('.htaccess'));
        // // foreach(glob(POP_SERVICEWORKERS_ASSETS_DIR."/*") as $file) {
        // //     copy($file, $this->getDir());
        // // }
    }
}

/**
 * Initialize
 */
global $pop_serviceworkers_manager;
$pop_serviceworkers_manager = new PoP_ServiceWorkersManager();
