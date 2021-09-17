<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ComponentContracts;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;

trait CategoryAPIRequestedContractTrait
{
    abstract protected function getCategoryTypeAPI(): CategoryTypeAPIInterface;
    abstract protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
}
