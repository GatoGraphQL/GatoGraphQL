<?php

/**
 * Date: 3/17/17
 *
 * @author Volodymyr Rashchepkin <rashepkin@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast;

trait AstDirectivesTrait
{
    /** @var array<string,Directive> */
    protected array $directives;

    /** @var array<string,Directive>|null */
    private ?array $directivesCache = null;

    public function hasDirectives(): bool
    {
        return count($this->directives) > 0;
    }

    public function hasDirective(string $name): bool
    {
        return array_key_exists($name, $this->directives);
    }

    public function getDirective(string $name): ?Directive
    {
        return $this->directives[$name] ?? null;
    }

    /**
     * @return array<string,Directive>
     */
    public function getDirectives(): array
    {
        return $this->directives;
    }

    /**
     * @param Directive[] $directives
     */
    public function setDirectives(array $directives): void
    {
        $this->directives      = [];
        $this->directivesCache = null;

        foreach ($directives as $directive) {
            $this->addDirective($directive);
        }
    }

    public function addDirective(Directive $directive): void
    {
        /**
         * Watch out! In this query, a field contains the same directive twice:
         *
         * ```
         * {
         *   user(id:1) {
         *     name @titleCase @upperCase @titleCase
         *   }
         * }
         * ```
         *
         * This behavior is allowed through `isRepeatable`:
         * @see https://spec.graphql.org/draft/#sel-FAJbLACvIDDxIAA6P
         *
         * The code here, originally, would not allow this behavior, since it
         * uses the directive's name as the key of the array, so if it happens
         * more than once, only one instance of it would remain:
         *
         * ```
         * $this->directives[$directive->getName()] = $directive;
         * ```
         *
         * As a solution, if the key already exists, place the directive
         * under a different key using a counter. There is no need to
         * remove this key later on, because the Parser will retrieve
         * the Directive objects stored as values in the array, and
         * ignore the key
         */
        $directiveName = $directive->getName();
        $key = $directiveName;
        $counter = 0;
        while (isset($this->directives[$key])) {
            $counter++;
            $key = $directiveName . '|' . $counter;
        }
        $this->directives[$key] = $directive;
    }
}
