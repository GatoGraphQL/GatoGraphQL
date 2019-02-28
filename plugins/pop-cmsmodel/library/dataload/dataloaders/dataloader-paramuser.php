<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_PARAMUSER', 'paramuser');
 
class Dataloader_ParamUser extends Dataloader_UserBase
{
    use Dataloader_ParamTrait;
    
    public function getName()
    {
        return GD_DATALOADER_PARAMUSER;
    }

    protected function getParamName()
    {
        return POP_INPUTNAME_USERID;
    }
}
    
/**
 * Initialize
 */
new Dataloader_ParamUser();
