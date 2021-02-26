<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use Leoloso\ExamplesForPoP\FieldResolvers\MeshRootFieldResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\AliasSchemaFieldResolverTrait;

/**
 * Demonstrate creating field aliases
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class MeshRootAliasFieldResolver extends AbstractDBDataFieldResolver
{
    use AliasSchemaFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'personalMeshServices',
            'personalMeshServiceData',
            'personalContentMesh',
        ];
    }

    protected function getAliasedFieldName(string $fieldName): string
    {
        $aliasFieldNames = [
            'personalMeshServices' => 'meshServices',
            'personalMeshServiceData' => 'meshServiceData',
            'personalContentMesh' => 'contentMesh',
        ];
        return $aliasFieldNames[$fieldName];
    }

    protected function getAliasedFieldResolverClass(): string
    {
        return MeshRootFieldResolver::class;
    }
}
