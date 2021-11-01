<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ComponentContracts;

use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;

interface TagAPIRequestedContractObjectTypeFieldResolverInterface
{
    public function getTagTypeAPI(): TagTypeAPIInterface;
    public function getTagTypeResolver(): TagObjectTypeResolverInterface;
}
