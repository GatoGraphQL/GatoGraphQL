<?php

declare(strict_types=1);

namespace PoP\Engine\FormInputs;

use PoP\ComponentModel\Facades\HelperServices\FormInputHelperServiceFacade;

class MultipleInputFormInput extends MultipleSelectFormInput
{
    public $subnames;

    public function getSubnames()
    {
        return $this->subnames;
    }

    public function __construct($params = array())
    {
        parent::__construct($params);
        $this->subnames = $params['subnames'] ? $params['subnames'] : array();
    }

    protected function getValueFromSource(array $source): mixed
    {
        $formInputHelperService = FormInputHelperServiceFacade::getInstance();
        $name = $this->getName();
        $value = array();
        foreach ($this->getSubnames() as $subname) {
            $fullSubname = $formInputHelperService->getMultipleInputName($name, $subname);
            if (isset($source[$fullSubname])) {
                $value[$subname] = $source[$fullSubname];
            }
        }

        // Only if there is any subfield value we assign it to $this->selected. Otherwise, it must keep the null value, as to obtain the value from dbObject[dbObjectField] in formcomponentValue
        if ($value) {
            return $value;
        }
        return null;
    }

    public function isInputSetInSource(?array $source = null): bool
    {
        $source = $this->getSource($source);

        $formInputHelperService = FormInputHelperServiceFacade::getInstance();
        $name = $this->getName();
        foreach ($this->getSubnames() as $subname) {
            $fullSubname = $formInputHelperService->getMultipleInputName($name, $subname);
            if (array_key_exists($fullSubname, $source)) {
                return true;
            }
        }
        return false;
    }
}
