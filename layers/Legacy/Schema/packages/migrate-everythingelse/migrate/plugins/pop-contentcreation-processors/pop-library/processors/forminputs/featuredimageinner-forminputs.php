<?php

class PoP_Module_Processor_FeaturedImageInnerComponentInputs extends PoP_Module_Processor_FeaturedImageInnerFormInputsBase
{
    public final const COMPONENT_FORMINPUT_FEATUREDIMAGEINNER = 'forminput-featuredimage-inner';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_FEATUREDIMAGEINNER,
        );
    }
}



