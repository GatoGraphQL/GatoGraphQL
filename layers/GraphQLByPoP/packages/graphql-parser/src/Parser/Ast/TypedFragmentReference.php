<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser\Ast;


use GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\FragmentInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;

class TypedFragmentReference extends AbstractAst implements FragmentInterface
{
    use AstDirectivesTrait;

    /** @var Field[]|Query[] */
    protected $fields;

    /** @var string */
    protected $typeName;

    /**
     * @param string          $typeName
     * @param Field[]|Query[] $fields
     * @param Directive[]     $directives
     * @param Location        $location
     */
    public function __construct($typeName, array $fields, array $directives, Location $location)
    {
        parent::__construct($location);

        $this->typeName = $typeName;
        $this->fields   = $fields;
        $this->setDirectives($directives);
    }

    /**
     * @return Field[]|Query[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param Field[]|Query[] $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @param string $typeName
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    }

}