<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use ReflectionClass;
use ReflectionProperty;

abstract class AbstractReflectionPropertyObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var ReflectionClass
     */
    protected ?ReflectionClass $reflectionInstance = null;
    /**
     * @var string[]|null
     */
    protected ?array $reflectionFieldNames = null;
    /**
     * @var array<string,string>|null
     */
    protected ?array $reflectionDocComments = null;

    abstract protected function getTypeClass(): string;

    protected function getReflectionInstance(): ReflectionClass
    {
        if ($this->reflectionInstance ===  null) {
            $this->reflectionInstance = new ReflectionClass($this->getTypeClass());
        }
        return $this->reflectionInstance;
    }

    protected function getReflectionPropertyFilters(): int
    {
        // By default: Return all public properties
        return ReflectionProperty::IS_PUBLIC;
    }

    /**
     * @return string[]
     */
    protected function getReflectionFieldNames(): array
    {
        if ($this->reflectionFieldNames === null) {
            $reflectionInstance = $this->getReflectionInstance();
            $reflectionProperties = $reflectionInstance->getProperties($this->getReflectionPropertyFilters());
            $this->reflectionFieldNames = array_map(
                fn (ReflectionProperty $property) =>  $property->getName(),
                $reflectionProperties
            );
        }
        return $this->reflectionFieldNames;
    }

    /**
     * Extract the description from the docComment
     * Adapted from https://github.com/nadirlc/comment-manager/blob/master/DescriptionParser.php
     */
    public function extractDescriptionText(string $docComment): string
    {
        // Remove "/**" and "*/". Taken from https://www.php.net/manual/en/reflectionclass.getdoccomment.php
        $docComment = trim(substr($docComment, 3, -2));
        // The comments are split on '*'
        $docCommentLines = explode("*", $docComment);
        $docCommentDescLines = [];
        for ($count = 1; $count < count($docCommentLines); $count++) {
            // The comment line. Carriage return and line feed are removed from the line
            $docCommentLines[$count] = str_replace(array("\r", "\n"), '', trim($docCommentLines[$count]));
            // If it is an empty line, skip it
            if (!$docCommentLines[$count]) {
                continue;
            }
            // If the line is empty or starts with a @, then it's a param, we reached the end of the description
            if (substr($docCommentLines[$count], 0, 1) === '@') {
                break;
            }
            // The line is added to the description
            $docCommentDescLines[] = $docCommentLines[$count];
        }
        return implode($this->__('. '), $docCommentDescLines);
    }

    /**
     * @return string[]
     */
    public function getTypePropertyDocComments(): array
    {
        if ($this->reflectionDocComments === null) {
            $reflectionInstance = $this->getReflectionInstance();
            $this->reflectionDocComments = [];
            foreach ($reflectionInstance->getProperties() as $property) {
                // If the property has a docblock comment, it will also include the symbols "/**" and "*/" and everything in between
                // Clean it a bit
                if ($docComment = $property->getDocComment()) {
                    $docComment = $this->extractDescriptionText($docComment);
                } else {
                    $docComment = '';
                }
                $this->reflectionDocComments[$property->getName()] = $docComment;
            }
        }
        return $this->reflectionDocComments;
    }

    /**
     * @return string[]
     */
    public function getPropertiesToExclude(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getPropertiesToInclude(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        // If explicitly stating what properties to include, then already use those
        if ($propertiesToInclude = $this->getPropertiesToInclude()) {
            return $propertiesToInclude;
        }
        // Otherwise, get all properties from the class, possibly excluding the forbidden ones (eg: user's "password" property)
        return array_diff(
            $this->getReflectionFieldNames(),
            $this->getPropertiesToExclude()
        );
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        // TODO: If we are running PHP 7.4, the properties may be typed,
        // so we can already get the type through reflection. Implement this!
        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        // TODO: If we are running PHP 7.4, the properties may be typed,
        // so we can already get the type through reflection. Implement this!
        return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        // Attempt to obtain the description from the docblock
        $reflectionDocComments = $this->getTypePropertyDocComments();
        return $reflectionDocComments[$fieldName] ?? parent::getFieldDescription($objectTypeResolver, $fieldName);
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // Simply return the value of the property in the object
        return $object->{$fieldDataAccessor->getFieldName()};
    }
}
