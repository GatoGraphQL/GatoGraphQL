<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Response;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

interface DatabaseEntryManagerInterface
{
    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $entries
     * @return array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>
     */
    public function moveEntriesWithIDUnderDBName(array $entries, RelationalTypeResolverInterface $relationalTypeResolver): array;
    /**
     * @param SplObjectStorage<FieldInterface,mixed> $entries
     * @return array<string,SplObjectStorage<FieldInterface,mixed>>
     */
    public function moveEntriesWithoutIDUnderDBName(
        SplObjectStorage $entries,
        RelationalTypeResolverInterface $relationalTypeResolver
    ): array;    
}
