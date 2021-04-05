<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ComponentContracts;

use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;

trait TagAPIRequestedContractTrait
{
    abstract protected function getTypeAPI(): TagTypeAPIInterface;
    abstract protected function getTypeResolverClass(): string;
}
