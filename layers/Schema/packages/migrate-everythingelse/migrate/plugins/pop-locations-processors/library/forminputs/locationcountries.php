<?php
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Engine\FormInputs\SelectFormInput;

class GD_FormInput_EM_LocationCountries extends SelectFormInput
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            em_get_countries()
        );

        return $values;
    }

    public function getDefaultValue()
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return $cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcomponent:addlocations:option:locationDefaultCountry'));
    }
}
