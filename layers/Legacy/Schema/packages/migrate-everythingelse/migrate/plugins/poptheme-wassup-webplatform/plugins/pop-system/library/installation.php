<?php
use PoP\FileStore\Facades\FileRendererFacade;

class PoPTheme_WassupWebPlatform_Installation
{
    public function __construct()
    {
        \PoP\Root\App::addAction('PoP:system-generate', $this->systemGenerate(...));
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
