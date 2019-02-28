<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_PARAMPOST', 'parampost');
 
class Dataloader_ParamPost extends Dataloader_PostBase
{
    use Dataloader_ParamTrait;
    
    public function getName()
    {
        return GD_DATALOADER_PARAMPOST;
    }

    protected function getParamName()
    {
        return POP_INPUTNAME_POSTID;
    }
}
    
/**
 * Initialize
 */
new Dataloader_ParamPost();
