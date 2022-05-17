<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ModuleContracts;

use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;

interface TagAPIRequestedContractObjectTypeFieldResolverInterface
{
    public function getTagTypeAPI(): TagTypeAPIInterface;
    public function getTagTypeResolver(): TagObjectTypeResolverInterface;
}
