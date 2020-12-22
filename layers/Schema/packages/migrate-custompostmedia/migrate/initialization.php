<?php
namespace PoPSchema\CustomPostMedia;

class Initialization
{
    public function initialize()
    {
        /**
         * Load the Config
         */
        include_once 'config/load.php';

        /**
         * Load the Kernel
         */
        include_once 'kernel/load.php';
    }
}
