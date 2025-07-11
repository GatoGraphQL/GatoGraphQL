<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\Taxonomies\Module;
use PoPCMSSchema\Taxonomies\ModuleConfiguration;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class TaxonomyTermPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TaxonomyTermPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate taxonomy terms', 'taxonomies');
    }

    protected function getDefaultLimit(): ?int
    {
        // @todo Add config for this and fix
        // /** @var ModuleConfiguration */
        // $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        // return $moduleConfiguration->getTaxonomyTermListDefaultLimit();
        return 10;
    }

    protected function getMaxLimit(): ?int
    {
        // @todo Add config for this and fix
        // /** @var ModuleConfiguration */
        // $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        // return $moduleConfiguration->getTaxonomyTermListMaxLimit();
        return 100;
    }
}
