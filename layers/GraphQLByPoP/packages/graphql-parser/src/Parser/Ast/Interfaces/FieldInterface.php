<?php

/**
 * Date: 03.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces;

use GraphQLByPoP\GraphQLParser\Parser\Ast\Argument;

interface FieldInterface extends LocatableInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getAlias();

    /**
     * @return Argument[]
     */
    public function getArguments();

    /**
     * @param string $name
     *
     * @return Argument
     */
    public function getArgument($name);

    /**
     * @return bool
     */
    public function hasFields();

    /**
     * @return array
     */
    public function getFields();

    /**
     * @return bool
     */
    public function hasDirectives();

    /**
     * @return Directive[]
     */
    public function getDirectives();
}
