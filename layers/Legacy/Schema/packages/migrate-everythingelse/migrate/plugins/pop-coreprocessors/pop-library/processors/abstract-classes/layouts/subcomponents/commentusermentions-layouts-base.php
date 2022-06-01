<?php

abstract class PoP_Module_Processor_CommentUserMentionsLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentField(\PoP\ComponentModel\Component\Component $component)
    {
        return 'taggedusers';
    }
}
