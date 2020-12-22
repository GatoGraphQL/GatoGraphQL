<?php
namespace PoPSchema\Media\WP;

class ObjectPropertyResolver extends \PoPSchema\Media\ObjectPropertyResolver_Base
{
    public function getMediaId($media)
    {
        return $media->ID;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
