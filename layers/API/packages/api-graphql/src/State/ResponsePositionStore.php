<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI\State;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

use function spl_object_id;

/**
 * Data used when producing a spec-compliant GraphQL response,
 * during a single response-formatting pass:
 *
 * - The response paths where each (object, field) was printed
 * - Which (object, field) positions already have an error
 * - The errors added while formatting the response
 *
 * @see https://spec.graphql.org/draft/#sec-Handling-Execution-Errors
 */
final class ResponsePositionStore
{
    /** @var array<string,array<string|int,array<int,array<array<string|int>>>>> */
    private array $responsePaths = [];
    /** @var array<string,array<string|int,array<int,true>>> */
    private array $erroredObjectFields = [];
    /** @var array<string,array<int,true>> */
    private array $erroredFields = [];
    /** @var array<array<string,mixed>> */
    private array $errorEntries = [];

    /**
     * @param array<string,ObjectTypeResolverInterface> $objectTypeResolvers Key: typeOutputKey
     */
    public function __construct(
        private readonly array $objectTypeResolvers,
    ) {
    }

    public function getObjectTypeResolver(string $typeOutputKey): ?ObjectTypeResolverInterface
    {
        return $this->objectTypeResolvers[$typeOutputKey] ?? null;
    }

    /**
     * @param array<string|int> $responsePath
     */
    public function addResponsePath(
        string $typeOutputKey,
        string|int $objectID,
        FieldInterface $field,
        array $responsePath,
    ): void {
        $this->responsePaths[$typeOutputKey][$objectID][spl_object_id($field)][] = $responsePath;
    }

    /**
     * @return array<array<string|int>>
     */
    public function getResponsePaths(
        string $typeOutputKey,
        string|int $objectID,
        FieldInterface $field,
    ): array {
        return $this->responsePaths[$typeOutputKey][$objectID][spl_object_id($field)] ?? [];
    }

    public function addErroredObjectField(
        string $typeOutputKey,
        string|int $objectID,
        FieldInterface $field,
    ): void {
        $this->erroredObjectFields[$typeOutputKey][$objectID][spl_object_id($field)] = true;
    }

    /**
     * The field has an error for all objects (eg: a schema error)
     */
    public function addErroredField(
        string $typeOutputKey,
        FieldInterface $field,
    ): void {
        $this->erroredFields[$typeOutputKey][spl_object_id($field)] = true;
    }

    public function hasError(
        string $typeOutputKey,
        string|int $objectID,
        FieldInterface $field,
    ): bool {
        $fieldID = spl_object_id($field);
        return isset($this->erroredFields[$typeOutputKey][$fieldID])
            || isset($this->erroredObjectFields[$typeOutputKey][$objectID][$fieldID]);
    }

    /**
     * @param array<string,mixed> $errorEntry
     */
    public function addErrorEntry(array $errorEntry): void
    {
        $this->errorEntries[] = $errorEntry;
    }

    /**
     * @return array<array<string,mixed>>
     */
    public function getErrorEntries(): array
    {
        return $this->errorEntries;
    }
}
