<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\ComponentModel\HelperServices\FormInputHelperServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class FormInputHelperServiceFacade
{
    public static function getInstance(): FormInputHelperServiceInterface
    {
        /**
         * @var FormInputHelperServiceInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(FormInputHelperServiceInterface::class);
        return $service;
    }
}
