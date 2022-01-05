<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\HelperServices\FormInputHelperServiceInterface;

class FormInputHelperServiceFacade
{
    public static function getInstance(): FormInputHelperServiceInterface
    {
        /**
         * @var FormInputHelperServiceInterface
         */
        $service = App::getContainer()->get(FormInputHelperServiceInterface::class);
        return $service;
    }
}
