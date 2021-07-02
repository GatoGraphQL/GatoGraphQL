<?php
namespace PoP\ApplicationWebPlatform\WP;

class FunctionAPI extends \PoP\EngineWebPlatform\WP\FunctionAPI
{
    public function getImagesDirectory()
    {
    	return parent::getImagesDirectory().'/img';
    }
    public function getImagesDirectoryURI()
    {
    	return parent::getImagesDirectoryURI().'/img';
    }
    public function getJSAssetsDirectory()
    {
    	return parent::getJSAssetsDirectory().'/js';
    }
    public function getJSAssetsDirectoryURI()
    {
    	return parent::getJSAssetsDirectoryURI().'/js';
    }
    public function getCSSAssetsDirectory()
    {
    	return parent::getCSSAssetsDirectory().'/css';
    }
    public function getCSSAssetsDirectoryURI()
    {
    	return parent::getCSSAssetsDirectoryURI().'/css';
    }
    public function getFontsDirectory()
    {
    	return parent::getFontsDirectory().'/css/fonts';
    }
    public function getFontsDirectoryURI()
    {
    	return parent::getFontsDirectoryURI().'/css/fonts';
    }
}

/**
 * Initialize
 */
new FunctionAPI();
