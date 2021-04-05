<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ComponentContracts;

use PoPSchema\Categories\Facades\CategoryTypeAPIFacade;
use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;

trait CategoryAPIRequestedContractTrait
{
    abstract protected function getTypeAPI(): CategoryTypeAPIInterface;
    abstract protected function getTypeResolverClass(): string;
    protected function getObjectPropertyAPI(): CategoryTypeAPIInterface
    {
        $cmscategoriesresolver = CategoryTypeAPIFacade::getInstance();
        return $cmscategoriesresolver;
    }
}
