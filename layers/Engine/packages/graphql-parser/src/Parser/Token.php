<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser;

class Token
{
    public const TYPE_END        = 'end';
    public const TYPE_IDENTIFIER = 'identifier';
    public const TYPE_NUMBER     = 'number';
    public const TYPE_STRING     = 'string';
    public const TYPE_ON         = 'on';

    public const TYPE_QUERY              = 'query';
    public const TYPE_MUTATION           = 'mutation';
    public const TYPE_FRAGMENT           = 'fragment';
    public const TYPE_FRAGMENT_REFERENCE = '...';
    public const TYPE_TYPED_FRAGMENT     = 'typed fragment';

    public const TYPE_LBRACE        = '{';
    public const TYPE_RBRACE        = '}';
    public const TYPE_LPAREN        = '(';
    public const TYPE_RPAREN        = ')';
    public const TYPE_LSQUARE_BRACE = '[';
    public const TYPE_RSQUARE_BRACE = ']';
    public const TYPE_COLON         = ':';
    public const TYPE_COMMA         = ',';
    public const TYPE_VARIABLE      = '$';
    public const TYPE_POINT         = '.';
    public const TYPE_REQUIRED      = '!';
    public const TYPE_EQUAL         = '=';
    public const TYPE_AT            = '@';

    public const TYPE_NULL  = 'null';
    public const TYPE_TRUE  = 'true';
    public const TYPE_FALSE = 'false';

    public function __construct(private string $type, private int $line, private int $column, private ?string $data = null)
    {
        if ($data) {
            $tokenLength = mb_strlen($data);
            $tokenLength = $tokenLength > 1 ? $tokenLength - 1 : 0;

            $this->column = $column - $tokenLength;
        }

        if ($this->getType() == self::TYPE_TRUE) {
            $this->data = true;
        }

        if ($this->getType() == self::TYPE_FALSE) {
            $this->data = false;
        }

        if ($this->getType() == self::TYPE_NULL) {
            $this->data = null;
        }
    }

    public static function tokenName(string $tokenType): string
    {
        return [
            self::TYPE_END                => 'END',
            self::TYPE_IDENTIFIER         => 'IDENTIFIER',
            self::TYPE_NUMBER             => 'NUMBER',
            self::TYPE_STRING             => 'STRING',
            self::TYPE_ON                 => 'ON',
            self::TYPE_QUERY              => 'QUERY',
            self::TYPE_MUTATION           => 'MUTATION',
            self::TYPE_FRAGMENT           => 'FRAGMENT',
            self::TYPE_FRAGMENT_REFERENCE => 'FRAGMENT_REFERENCE',
            self::TYPE_TYPED_FRAGMENT     => 'TYPED_FRAGMENT',
            self::TYPE_LBRACE             => 'LBRACE',
            self::TYPE_RBRACE             => 'RBRACE',
            self::TYPE_LPAREN             => 'LPAREN',
            self::TYPE_RPAREN             => 'RPAREN',
            self::TYPE_LSQUARE_BRACE      => 'LSQUARE_BRACE',
            self::TYPE_RSQUARE_BRACE      => 'RSQUARE_BRACE',
            self::TYPE_COLON              => 'COLON',
            self::TYPE_COMMA              => 'COMMA',
            self::TYPE_VARIABLE           => 'VARIABLE',
            self::TYPE_POINT              => 'POINT',
            self::TYPE_NULL               => 'NULL',
            self::TYPE_TRUE               => 'TRUE',
            self::TYPE_FALSE              => 'FALSE',
            self::TYPE_REQUIRED           => 'REQUIRED',
            self::TYPE_AT                 => 'AT',
        ][$tokenType];
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getColumn(): int
    {
        return $this->column;
    }
}
