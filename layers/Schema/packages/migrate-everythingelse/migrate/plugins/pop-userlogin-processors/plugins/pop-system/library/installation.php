<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\FileStore\Facades\FileRendererFacade;

class PoP_CoreProcessors_Installation
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction('PoP:system-generate', array($this, 'systemGenerate'));
    }

    public function systemGenerate()
    {
        global $popcore_userloggedinstyles_file;

        // User Logged-in CSS
        // $popcore_userloggedinstyles_file->generate();
        FileRendererFacade::getInstance()->renderAndSave($popcore_userloggedinstyles_file);
    }
}

/**
 * Initialization
 */
new PoP_CoreProcessors_Installation();
