<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_PARAMCOMMENT', 'paramcomment');
 
class Dataloader_ParamComment extends Dataloader_CommentBase
{
    use Dataloader_ParamTrait;

    public function getName()
    {
        return GD_DATALOADER_PARAMCOMMENT;
    }

    protected function getParamName()
    {
        return POP_INPUTNAME_COMMENTID;
    }
}
    
/**
 * Initialize
 */
new Dataloader_ParamComment();
