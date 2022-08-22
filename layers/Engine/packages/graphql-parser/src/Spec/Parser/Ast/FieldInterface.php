<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface FieldInterface extends AstInterface, LocatableInterface, WithDirectivesInterface, WithNameInterface, WithArgumentsInterface
{
    public function getName(): string;

    public function getAlias(): ?string;

    /**
     * @return Argument[]
     */
    public function getArguments(): array;

    public function getArgument(string $name): ?Argument;

    public function asFieldOutputQueryString(): string;

    /**
     * This function uniquely identifies the field in the query.
     * In this query:
     *
     *   { queryType { name } mutationType { name } }
     *
     * fields `name` are different fields (even if under the same type).
     *
     * To enforce this, they must include the location in the query.
     *
     * This is important to enable the SiteBuilder to process the same
     * field added by different components all together. Created on runtime,
     * these will all have location `ASTNodesFactory::getNonSpecificLocation()`
     * => (1x1), hence even if the Location objects are different, they will
     * be treated as the same one, and all their IDs can be processed together.
     */
    public function getUniqueID(): string;

    /**
     * The entry in the JSON output for the field.
     * If the alias is set, use it. Otherwise, use the name,
     * discarding the args.
     *
     * @see https://spec.graphql.org/draft/#sec-Field-Alias
     */
    public function getOutputKey(): string;

    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     *
     * Watch out: `{ title: title }` is equivalent to `{ title }`
     */
    public function equalsTo(FieldInterface $field): bool;
}
