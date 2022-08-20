<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\Engine\FormInputs\SelectFormInput;
use PoP\LooseContracts\Facades\NameResolverFacade;

class GD_FormInput_EM_LocationCountries extends SelectFormInput
{
    /**
     * @return mixed[]
     */
    public function getAllValues(string $label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            em_get_countries()
        );

        return $values;
    }

    public function getDefaultValue(): mixed
    {
        $cmsService = CMSServiceFacade::getInstance();
        return $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcomponent:addlocations:option:locationDefaultCountry'));
    }
}
