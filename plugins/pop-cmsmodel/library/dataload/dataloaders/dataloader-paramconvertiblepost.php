<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_PARAMCONVERTIBLEPOST', 'paramconvertiblepost');
 
class Dataloader_ParamConvertiblePost extends Dataloader_ParamPost
{
    public function getName()
    {
        return GD_DATALOADER_PARAMCONVERTIBLEPOST;
    }

    public function getFieldprocessor()
    {
        return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS;
    }
}
    
/**
 * Initialize
 */
new Dataloader_ParamConvertiblePost();
