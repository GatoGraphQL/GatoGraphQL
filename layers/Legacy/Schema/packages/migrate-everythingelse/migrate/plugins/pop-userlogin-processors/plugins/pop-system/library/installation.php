<?php
use PoP\FileStore\Facades\FileRendererFacade;

class PoP_CoreProcessors_Installation
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction('PoP:system-generate', array($this, 'systemGenerate'));
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
