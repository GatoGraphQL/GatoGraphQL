<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ComponentContracts;

use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;

trait TagAPIRequestedContractTrait
{
    abstract protected function getTagTypeAPI(): TagTypeAPIInterface;
    abstract protected function getTagTypeResolver(): TagObjectTypeResolverInterface;
}
