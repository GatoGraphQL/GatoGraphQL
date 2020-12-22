<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractUnionTypeResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\CustomPosts\TypeDataLoaders\CustomPostUnionTypeDataLoader;

class CustomPostUnionTypeResolver extends AbstractUnionTypeResolver
{
    public const NAME = 'CustomPostUnion';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Union of \'custom post\' type resolvers', 'customposts');
    }

    public function getTypeDataLoaderClass(): string
    {
        return CustomPostUnionTypeDataLoader::class;
    }

    public function getSchemaTypeInterfaceClass(): ?string
    {
        return IsCustomPostFieldInterfaceResolver::class;
    }
}
