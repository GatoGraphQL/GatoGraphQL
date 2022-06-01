<?php

abstract class PoP_Module_Processor_PostAuthorLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentField(\PoP\ComponentModel\Component\Component $component)
    {
        return 'authors';
    }
}
