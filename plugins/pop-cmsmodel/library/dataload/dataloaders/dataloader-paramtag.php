<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_PARAMTAG', 'paramtag');
 
class Dataloader_ParamTag extends Dataloader_TagBase
{
    use Dataloader_ParamTrait;
    
    public function getName()
    {
        return GD_DATALOADER_PARAMTAG;
    }

    protected function getParamName()
    {
        return POP_INPUTNAME_TAGID;
    }
}
    
/**
 * Initialize
 */
new Dataloader_ParamTag();
