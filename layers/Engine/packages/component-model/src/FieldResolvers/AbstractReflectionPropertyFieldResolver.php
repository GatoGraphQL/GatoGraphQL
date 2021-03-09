<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use ReflectionClass;
use ReflectionProperty;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;

abstract class AbstractReflectionPropertyFieldResolver extends AbstractDBDataFieldResolver
{
    protected static $reflectionInstance;
    protected static $reflectionFieldNames;
    protected static $reflectionDocComments;

    abstract protected static function getTypeClass(): string;

    protected static function getReflectionInstance(): ReflectionClass
    {
        if (is_null(self::$reflectionInstance)) {
            $class = get_called_class();
            self::$reflectionInstance = new ReflectionClass($class::getTypeClass());
        }
        return self::$reflectionInstance;
    }

    protected static function getReflectionPropertyFilters(): int
    {
        // By default: Return all public properties
        return ReflectionProperty::IS_PUBLIC;
    }

    protected static function getReflectionFieldNames(): array
    {
        if (is_null(self::$reflectionFieldNames)) {
            $reflectionInstance = self::getReflectionInstance();
            $class = get_called_class();
            $reflectionProperties = $reflectionInstance->getProperties($class::getReflectionPropertyFilters());
            self::$reflectionFieldNames = array_map(
                function ($property) {
                    return $property->getName();
                },
                $reflectionProperties
            );
        }
        return self::$reflectionFieldNames;
    }

    /**
     * Extract the description from the docComment
     * Adapted from https://github.com/nadirlc/comment-manager/blob/master/DescriptionParser.php
     *
     * @param string $docComment
     * @return string
     */
    public static function extractDescriptionText(string $docComment): string
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
            if (substr($docCommentLines[$count], 0, 1) == '@') {
                break;
            }
            // The line is added to the description
            $docCommentDescLines[] = $docCommentLines[$count];
        }
        $translationAPI = TranslationAPIFacade::getInstance();
        return implode($translationAPI->__('. '), $docCommentDescLines);
    }

    public static function getTypePropertyDocComments(): array
    {
        if (is_null(self::$reflectionDocComments)) {
            $reflectionInstance = self::getReflectionInstance();
            self::$reflectionDocComments = [];
            foreach ($reflectionInstance->getProperties() as $property) {
                // If the property has a docblock comment, it will also include the symbols "/**" and "*/" and everything in between
                // Clean it a bit
                if ($docComment = $property->getDocComment()) {
                    $docComment = self::extractDescriptionText($docComment);
                } else {
                    $docComment = '';
                }
                self::$reflectionDocComments[$property->getName()] = $docComment;
            }
        }
        return self::$reflectionDocComments;
    }

    public static function getPropertiesToExclude(): array
    {
        return [];
    }

    public static function getPropertiesToInclude(): array
    {
        return [];
    }

    public function getFieldNamesToResolve(): array
    {
        // If explicitly stating what properties to include, then already use those
        $class = get_called_class();
        if ($propertiesToInclude = $class::getPropertiesToInclude()) {
            return $propertiesToInclude;
        }
        // Otherwise, get all properties from the class, possibly excluding the forbidden ones (eg: user's "password" property)
        return array_diff(
            self::getReflectionFieldNames(),
            $class::getPropertiesToExclude()
        );
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        // TODO: If we are running PHP 7.4, the properties may be typed,
        // so we can already get the type through reflection. Implement this!
        return parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        // TODO: If we are running PHP 7.4, the properties may be typed,
        // so we can already get the type through reflection. Implement this!
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        // Attempt to obtain the description from the docblock
        $reflectionDocComments = self::getTypePropertyDocComments();
        return $reflectionDocComments[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        // Simply return the value of the property in the object
        return $resultItem->$fieldName;
    }
}
