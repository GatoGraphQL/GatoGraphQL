<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ComponentContracts;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;

interface CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    public function getCategoryTypeAPI(): CategoryTypeAPIInterface;
    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
}
