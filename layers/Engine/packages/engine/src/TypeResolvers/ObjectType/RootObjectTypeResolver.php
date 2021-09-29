<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\RelationalTypeDataLoaders\ObjectType\RootTypeDataLoader;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeResolver extends AbstractObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;

    public const HOOK_DESCRIPTION = __CLASS__ . ':description';
    protected RootTypeDataLoader $rootTypeDataLoader;

    #[Required]
    public function autowireRootObjectTypeResolver(
        RootTypeDataLoader $rootTypeDataLoader,
    ): void {
        $this->rootTypeDataLoader = $rootTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'Root';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->hooksAPI->applyFilters(
            self::HOOK_DESCRIPTION,
            $this->translationAPI->__('Root type, starting from which the query is executed', 'api')
        );
    }

    public function getID(object $object): string | int | null
    {
        /** @var Root */
        $root = $object;
        return $root->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->rootTypeDataLoader;
    }

    protected function addSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = []): void
    {
        parent::addSchemaDefinition($stackMessages, $generalMessages, $options);

        // Only in the root we output the operators and helpers
        $typeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($this);

        // Add the directives (global)
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->getSchemaDirectiveResolvers(true);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES][$directiveName] = $this->getDirectiveSchemaDefinition($directiveResolver, $options);
        }

        // Add the fields (global)
        $schemaObjectTypeFieldResolvers = $this->getSchemaObjecTypeObjectTypeFieldResolvers(true);
        foreach ($schemaObjectTypeFieldResolvers as $fieldName => $objectTypeFieldResolver) {
            $this->addFieldSchemaDefinition($objectTypeFieldResolver, $fieldName, $stackMessages, $generalMessages, $options);
        }
    }
}
