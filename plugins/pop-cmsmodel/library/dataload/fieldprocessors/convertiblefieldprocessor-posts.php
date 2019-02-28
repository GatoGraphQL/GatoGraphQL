<?php
namespace PoP\CMSModel;

define('GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS', 'convertible-posts');

class ConvertibleFieldProcessor_Posts extends \PoP\Engine\ConvertibleFieldProcessorBase
{
    public function getName()
    {
        return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS;
    }

    protected function getDefaultFieldprocessor()
    {
        return GD_DATALOAD_FIELDPROCESSOR_POSTS;
    }
}

/**
 * Initialize
 */
new ConvertibleFieldProcessor_Posts();
