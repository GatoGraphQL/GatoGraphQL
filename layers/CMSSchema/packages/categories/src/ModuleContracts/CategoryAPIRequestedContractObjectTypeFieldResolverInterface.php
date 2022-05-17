<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ModuleContracts;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;

interface CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    public function getCategoryTypeAPI(): CategoryTypeAPIInterface;
    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
}
