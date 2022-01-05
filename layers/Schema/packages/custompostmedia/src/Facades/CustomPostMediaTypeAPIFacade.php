<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\Facades;

use PoP\Engine\App;
use PoPSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;

class CustomPostMediaTypeAPIFacade
{
    public static function getInstance(): CustomPostMediaTypeAPIInterface
    {
        /**
         * @var CustomPostMediaTypeAPIInterface
         */
        $service = App::getContainer()->get(CustomPostMediaTypeAPIInterface::class);
        return $service;
    }
}
