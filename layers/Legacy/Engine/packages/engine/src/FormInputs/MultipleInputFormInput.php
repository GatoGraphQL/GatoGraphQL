<?php

declare(strict_types=1);

namespace PoP\Engine\FormInputs;

use PoP\Engine\Facades\HelperServices\FormInputHelperServiceFacade;
use PoP\Root\App;

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

    protected function getValueFromSource(?array $source = null): mixed
    {
        $formInputHelperService = FormInputHelperServiceFacade::getInstance();
        $name = $this->getName();
        $value = array();
        foreach ($this->getSubnames() as $subname) {
            $fullSubname = $formInputHelperService->getMultipleInputName($name, $subname);
            $subValue = $this->getValueFromSourceOrRequest($source, $fullSubname);
            if ($subValue !== null) {
                $value[$subname] = $subValue;
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
        $formInputHelperService = FormInputHelperServiceFacade::getInstance();
        $name = $this->getName();
        foreach ($this->getSubnames() as $subname) {
            $fullSubname = $formInputHelperService->getMultipleInputName($name, $subname);
            if (
                ($source !== null && array_key_exists($fullSubname, $source))
                || App::getRequest()->request->has($fullSubname)
                || App::getRequest()->query->has($fullSubname)
            ) {
                return true;
            }
        }
        return false;
    }
}
