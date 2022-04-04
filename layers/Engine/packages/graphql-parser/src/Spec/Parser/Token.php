<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

class Token
{
    public final const TYPE_END        = 'end';
    public final const TYPE_IDENTIFIER = 'identifier';
    public final const TYPE_NUMBER     = 'number';
    public final const TYPE_STRING     = 'string';
    public final const TYPE_ON         = 'on';

    public final const TYPE_QUERY              = 'query';
    public final const TYPE_MUTATION           = 'mutation';
    public final const TYPE_FRAGMENT           = 'fragment';
    public final const TYPE_FRAGMENT_REFERENCE = '...';
    public final const TYPE_INLINE_FRAGMENT     = 'inline fragment';

    public final const TYPE_LBRACE        = '{';
    public final const TYPE_RBRACE        = '}';
    public final const TYPE_LPAREN        = '(';
    public final const TYPE_RPAREN        = ')';
    public final const TYPE_LSQUARE_BRACE = '[';
    public final const TYPE_RSQUARE_BRACE = ']';
    public final const TYPE_COLON         = ':';
    public final const TYPE_COMMA         = ',';
    public final const TYPE_VARIABLE      = '$';
    public final const TYPE_POINT         = '.';
    public final const TYPE_REQUIRED      = '!';
    public final const TYPE_EQUAL         = '=';
    public final const TYPE_AT            = '@';

    public final const TYPE_NULL  = 'null';
    public final const TYPE_TRUE  = 'true';
    public final const TYPE_FALSE = 'false';

    public function __construct(private string $type, private int $line, private int $column, private string|int|float|bool|null $data = null)
    {
        if ($data) {
            $tokenLength = mb_strlen((string)$data);
            $tokenLength = $tokenLength > 1 ? $tokenLength - 1 : 0;

            $this->column = $column - $tokenLength;
        }

        if ($this->getType() === self::TYPE_TRUE) {
            $this->data = true;
        }

        if ($this->getType() === self::TYPE_FALSE) {
            $this->data = false;
        }

        if ($this->getType() === self::TYPE_NULL) {
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
            self::TYPE_INLINE_FRAGMENT     => 'TYPED_FRAGMENT',
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
        ][$tokenType] ?? $tokenType;
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
