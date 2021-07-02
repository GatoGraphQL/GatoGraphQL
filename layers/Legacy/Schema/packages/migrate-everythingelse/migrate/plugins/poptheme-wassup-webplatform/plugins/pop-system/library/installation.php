<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\FileStore\Facades\FileRendererFacade;

class PoPTheme_WassupWebPlatform_Installation
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction('PoP:system-generate', array($this, 'systemGenerate'));
    }

    public function systemGenerate()
    {
        global $popthemewassup_backgroundimage_file, $popthemewassup_feedthumb_file;
        // $popthemewassup_backgroundimage_file->generate();
        FileRendererFacade::getInstance()->renderAndSave($popthemewassup_backgroundimage_file);
        // $popthemewassup_feedthumb_file->generate();
        FileRendererFacade::getInstance()->renderAndSave($popthemewassup_feedthumb_file);
    }
}

/**
 * Initialization
 */
new PoPTheme_WassupWebPlatform_Installation();
