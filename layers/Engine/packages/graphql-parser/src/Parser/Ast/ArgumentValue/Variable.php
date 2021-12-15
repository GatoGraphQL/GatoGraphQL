<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Parser\Ast\Interfaces\ValueInterface;
use PoP\GraphQLParser\Parser\Location;

class Variable extends AbstractAst implements ValueInterface
{
    private mixed $value;

    private bool $used = false;

    private bool $hasDefaultValue = false;

    private mixed $defaultValue = null;

    public function __construct(private string $name, private string $type, private bool $nullable, private bool $isArray, private bool $arrayElementNullable, Location $location)
    {
        parent::__construct($location);
    }

    /**
     * @return mixed
     *
     * @throws \LogicException
     */
    public function getValue()
    {
        if (null === $this->value) {
            if ($this->hasDefaultValue()) {
                return $this->defaultValue;
            }
            throw new \LogicException('Value is not set for variable "' . $this->name . '"');
        }

        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setTypeName($type): void
    {
        $this->type = $type;
    }

    /**
     * @return boolean
     */
    public function isArray()
    {
        return $this->isArray;
    }

    /**
     * @param boolean $isArray
     */
    public function setIsArray($isArray): void
    {
        $this->isArray = $isArray;
    }

    /**
     * @return boolean
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @param boolean $nullable
     */
    public function setNullable($nullable): void
    {
        $this->nullable = $nullable;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     */
    public function setDefaultValue($defaultValue): void
    {
        $this->hasDefaultValue = true;

        $this->defaultValue = $defaultValue;
    }

    /**
     * @return boolean
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * @param boolean $used
     *
     * @return $this
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * @return bool
     */
    public function isArrayElementNullable()
    {
        return $this->arrayElementNullable;
    }

    /**
     * @param bool $arrayElementNullable
     */
    public function setArrayElementNullable($arrayElementNullable): void
    {
        $this->arrayElementNullable = $arrayElementNullable;
    }
}
