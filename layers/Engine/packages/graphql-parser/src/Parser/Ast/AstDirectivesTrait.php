<?php

/**
 * Date: 3/17/17
 *
 * @author Volodymyr Rashchepkin <rashepkin@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast;

trait AstDirectivesTrait
{

    /** @var Directive[] */
    protected $directives;

    private $directivesCache = null;


    public function hasDirectives()
    {
        return (bool)count($this->directives);
    }

    public function hasDirective($name)
    {
        return array_key_exists($name, $this->directives);
    }

    /**
     * @param $name
     *
     * @return null|Directive
     */
    public function getDirective($name)
    {
        $directive = null;
        if (isset($this->directives[$name])) {
            $directive = $this->directives[$name];
        }

        return $directive;
    }

    /**
     * @return Directive[]
     */
    public function getDirectives()
    {
        return $this->directives;
    }

    /**
     * @param $directives Directive[]
     */
    public function setDirectives(array $directives)
    {
        $this->directives      = [];
        $this->directivesCache = null;

        foreach ($directives as $directive) {
            $this->addDirective($directive);
        }
    }

    public function addDirective(Directive $directive)
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
