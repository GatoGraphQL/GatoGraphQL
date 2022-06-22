<?php

abstract class PoP_Module_Processor_PostUserMentionsLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentFieldNode(\PoP\ComponentModel\Component\Component $component)
    {
        return 'taggedusers';
    }
}
