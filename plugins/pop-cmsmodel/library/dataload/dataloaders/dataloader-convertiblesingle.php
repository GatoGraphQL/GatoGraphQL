<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_CONVERTIBLESINGLE', 'convertible-single');

class Dataloader_ConvertibleSingle extends Dataloader_PostBase
{
    use Dataloader_SingleTrait;

    public function getName()
    {
        return GD_DATALOADER_CONVERTIBLESINGLE;
    }

    public function getFieldprocessor()
    {
        return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS;
    }
}
    

/**
 * Initialize
 */
new Dataloader_ConvertibleSingle();
