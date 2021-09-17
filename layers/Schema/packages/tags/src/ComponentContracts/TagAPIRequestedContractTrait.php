<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ComponentContracts;

use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;

trait TagAPIRequestedContractTrait
{
    abstract public function getTagTypeAPI(): TagTypeAPIInterface;
    abstract public function getTagTypeResolver(): TagObjectTypeResolverInterface;
}
