<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Class to be used only when a generic CustomPost type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CustomPostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    protected CustomPostTypeDataLoader $customPostTypeDataLoader;

    #[Required]
    public function autowireCustomPostObjectTypeResolver(
        CustomPostTypeDataLoader $customPostTypeDataLoader,
    ): void {
        $this->customPostTypeDataLoader = $customPostTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPost';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a custom post', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->customPostTypeDataLoader;
    }
}
