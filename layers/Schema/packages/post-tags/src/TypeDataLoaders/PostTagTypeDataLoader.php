<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\TypeDataLoaders;

use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\Tags\TypeDataLoaders\AbstractTagTypeDataLoader;

class PostTagTypeDataLoader extends AbstractTagTypeDataLoader
{
    use PostTagAPISatisfiedContractTrait;

    public function getFilterDataloadingModule(): ?array
    {
        return [\PoP_PostTags_Module_Processor_FieldDataloads::class, \PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST];
    }
}
