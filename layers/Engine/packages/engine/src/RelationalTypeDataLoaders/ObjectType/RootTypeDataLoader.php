<?php

declare(strict_types=1);

namespace PoP\Engine\RelationalTypeDataLoaders\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\ObjectModels\Root;

class RootTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected Root $root;

    #[Required]
    public function autowireRootTypeDataLoader(
        Root $root,
    ): void {
        $this->root = $root;
    }

    public function getObjects(array $ids): array
    {
        return [
            $this->root,
        ];
    }
}
