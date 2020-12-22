<?php
namespace PoPSchema\CustomPosts\WP;

class Initialization
{
    public function initialize()
    {
        /**
         * Load the Contract Implementations
         */
        include_once 'contractimplementations/load.php';
    }
}
