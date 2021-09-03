<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ComponentContracts;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;

trait CategoryAPIRequestedContractTrait
{
    abstract protected function getCategoryTypeAPI(): CategoryTypeAPIInterface;
    abstract protected function getCategoryTypeResolverClass(): string;
}
