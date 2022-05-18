<?php

abstract class PoP_Module_Processor_PostAuthorLayoutsBase extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public function getSubcomponentField(array $component)
    {
        return 'authors';
    }
}
