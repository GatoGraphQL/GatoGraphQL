<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

class PoP_AvatarFoundationManager
{
    public $sizes;

    public function __construct()
    {
        PoP_AvatarFoundationManagerFactory::setInstance($this);
        $this->sizes = array();
    }

    public function addSize($size)
    {
        $this->addSizes(array($size));
    }

    public function addSizes($sizes)
    {
        $this->sizes = array_unique(
            array_merge(
                $this->sizes,
                $sizes
            )
        );
    }

    public function getSizes()
    {
        return $this->sizes;
    }

    // public function getNames()
    // {
    //     return array_map($this->getName(...), $this->sizes);
    // }

    public function getAvatarField($size)
    {
        // return 'avatar-'.$size;
        return FieldQueryInterpreterFacade::getInstance()->getField('avatar', ['size' => $size], 'avatar-'.$size);
    }

    // public function getSize($name)
    // {
    //     return substr($name, strlen('avatar-'));
    // }
}

/**
 * Initialize
 */
new PoP_AvatarFoundationManager();
