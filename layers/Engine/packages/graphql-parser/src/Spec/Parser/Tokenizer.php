<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Error\GraphQLErrorMessageProviderInterface;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\Root\Services\BasicServiceTrait;

class Tokenizer
{
    use BasicServiceTrait;

    protected string $source;
    protected int $pos = 0;
    protected int $line = 1;
    protected int $lineStart = 0;
    protected Token $lookAhead;

    private ?GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider = null;

    final public function setGraphQLErrorMessageProvider(GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider): void
    {
        $this->graphQLErrorMessageProvider = $graphQLErrorMessageProvider;
    }
    final protected function getGraphQLErrorMessageProvider(): GraphQLErrorMessageProviderInterface
    {
        return $this->graphQLErrorMessageProvider ??= $this->instanceManager->getInstance(GraphQLErrorMessageProviderInterface::class);
    }

    protected function initTokenizer(string $source): void
    {
        $this->resetTokenizer();
        $this->source    = $source;
        $this->lookAhead = $this->next();
    }

    protected function resetTokenizer(): void
    {
        $this->pos = 0;
        $this->line = 1;
        $this->lineStart = 0;
    }

    protected function next(): Token
    {
        $this->skipWhitespace();

        return $this->scan();
    }

    protected function skipWhitespace(): void
    {
        while ($this->pos < strlen($this->source)) {
            $ch = $this->source[$this->pos];
            if ($ch === ' ' || $ch === "\t" || $ch === ',') {
                $this->pos++;
            } elseif ($ch === '#') {
                $this->pos++;
                while (
                    $this->pos < strlen($this->source) &&
                    ($code = ord($this->source[$this->pos])) &&
                    $code !== 10 && $code !== 13 && $code !== 0x2028 && $code !== 0x2029
                ) {
                    $this->pos++;
                }
            } elseif ($ch === "\r") {
                $this->pos++;
                if ($this->source[$this->pos] === "\n") {
                    $this->pos++;
                }
                $this->line++;
                $this->lineStart = $this->pos;
            } elseif ($ch === "\n") {
                $this->pos++;
                $this->line++;
                $this->lineStart = $this->pos;
            } else {
                break;
            }
        }
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function scan(): Token
    {
        if ($this->pos >= strlen($this->source)) {
            return new Token(Token::TYPE_END, $this->getLine(), $this->getColumn());
        }

        $ch = $this->source[$this->pos];
        switch ($ch) {
            case Token::TYPE_LPAREN:
                ++$this->pos;

                return new Token(Token::TYPE_LPAREN, $this->getLine(), $this->getColumn());
            case Token::TYPE_RPAREN:
                ++$this->pos;

                return new Token(Token::TYPE_RPAREN, $this->getLine(), $this->getColumn());
            case Token::TYPE_LBRACE:
                ++$this->pos;

                return new Token(Token::TYPE_LBRACE, $this->getLine(), $this->getColumn());
            case Token::TYPE_RBRACE:
                ++$this->pos;

                return new Token(Token::TYPE_RBRACE, $this->getLine(), $this->getColumn());
            case Token::TYPE_COMMA:
                ++$this->pos;

                return new Token(Token::TYPE_COMMA, $this->getLine(), $this->getColumn());
            case Token::TYPE_LSQUARE_BRACE:
                ++$this->pos;

                return new Token(Token::TYPE_LSQUARE_BRACE, $this->getLine(), $this->getColumn());
            case Token::TYPE_RSQUARE_BRACE:
                ++$this->pos;

                return new Token(Token::TYPE_RSQUARE_BRACE, $this->getLine(), $this->getColumn());
            case Token::TYPE_REQUIRED:
                ++$this->pos;

                return new Token(Token::TYPE_REQUIRED, $this->getLine(), $this->getColumn());
            case Token::TYPE_AT:
                ++$this->pos;

                return new Token(Token::TYPE_AT, $this->getLine(), $this->getColumn());
            case Token::TYPE_COLON:
                ++$this->pos;

                return new Token(Token::TYPE_COLON, $this->getLine(), $this->getColumn());

            case Token::TYPE_EQUAL:
                ++$this->pos;

                return new Token(Token::TYPE_EQUAL, $this->getLine(), $this->getColumn());

            case Token::TYPE_POINT:
                if ($this->checkFragment()) {
                    return new Token(Token::TYPE_FRAGMENT_REFERENCE, $this->getLine(), $this->getColumn());
                }

                return new Token(Token::TYPE_POINT, $this->getLine(), $this->getColumn());


            case Token::TYPE_VARIABLE:
                ++$this->pos;

                return new Token(Token::TYPE_VARIABLE, $this->getLine(), $this->getColumn());
        }

        if ($ch === '_' || ('a' <= $ch && $ch <= 'z') || ('A' <= $ch && $ch <= 'Z')) {
            return $this->scanWord();
        }

        if ($ch === '-' || ('0' <= $ch && $ch <= '9')) {
            return $this->scanNumber();
        }

        if ($ch === '"') {
            return $this->scanString();
        }

        throw $this->createException($this->getGraphQLErrorMessageProvider()->getCantRecognizeTokenTypeErrorMessage());
    }

    protected function checkFragment(): bool
    {
        $this->pos++;
        $ch = $this->source[$this->pos];

        $this->pos++;
        $nextCh = $this->source[$this->pos];

        $isset = $ch == Token::TYPE_POINT && $nextCh == Token::TYPE_POINT;

        if ($isset) {
            $this->pos++;

            return true;
        }

        return false;
    }

    protected function scanWord(): Token
    {
        $start = $this->pos;
        $this->pos++;

        while ($this->pos < strlen($this->source)) {
            $ch = $this->source[$this->pos];

            if ($ch === '_' || $ch === '$' || ('a' <= $ch && $ch <= 'z') || ('A' <= $ch && $ch <= 'Z') || ('0' <= $ch && $ch <= '9')) {
                $this->pos++;
            } else {
                break;
            }
        }

        $value = substr($this->source, $start, $this->pos - $start);

        return new Token($this->getKeyword($value), $this->getLine(), $this->getColumn(), $value);
    }

    protected function getKeyword(string $name): string
    {
        return match ($name) {
            'null' => Token::TYPE_NULL,
            'true' => Token::TYPE_TRUE,
            'false' => Token::TYPE_FALSE,
            'query' => Token::TYPE_QUERY,
            'fragment' => Token::TYPE_FRAGMENT,
            'mutation' => Token::TYPE_MUTATION,
            'on' => Token::TYPE_ON,
            default => Token::TYPE_IDENTIFIER,
        };
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function expect(string $type): Token
    {
        if ($this->match($type)) {
            return $this->lex();
        }

        throw $this->createUnexpectedException($this->peek());
    }

    protected function match(string $type): bool
    {
        return $this->peek()->getType() === $type;
    }

    protected function scanNumber(): Token
    {
        $start = $this->pos;
        if ($this->source[$this->pos] === '-') {
            ++$this->pos;
        }

        $this->skipInteger();

        if (isset($this->source[$this->pos]) && $this->source[$this->pos] === '.') {
            $this->pos++;
            $this->skipInteger();
        }

        $value = substr($this->source, $start, $this->pos - $start);

        if (!str_contains($value, '.')) {
            $value = (int) $value;
        } else {
            $value = (float) $value;
        }

        return new Token(Token::TYPE_NUMBER, $this->getLine(), $this->getColumn(), $value);
    }

    protected function skipInteger(): void
    {
        while ($this->pos < strlen($this->source)) {
            $ch = $this->source[$this->pos];
            if ('0' <= $ch && $ch <= '9') {
                $this->pos++;
            } else {
                break;
            }
        }
    }

    protected function createException(string $message): SyntaxErrorException
    {
        return new SyntaxErrorException($message, $this->getLocation());
    }

    protected function getLocation(): Location
    {
        return new Location($this->getLine(), $this->getColumn());
    }

    protected function getColumn(): int
    {
        return $this->pos - $this->lineStart;
    }

    protected function getLine(): int
    {
        return $this->line;
    }

    /**
     * @throws SyntaxErrorException
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     */
    protected function scanString(): Token
    {
        $len = strlen($this->source);
        $this->pos++;

        $value = '';
        while ($this->pos < $len) {
            $ch = $this->source[$this->pos];
            if ($ch === '"') {
                $token = new Token(Token::TYPE_STRING, $this->getLine(), $this->getColumn(), $value);
                $this->pos++;

                return $token;
            }

            if ($ch === '\\' && ($this->pos < ($len - 1))) {
                $this->pos++;
                $ch = $this->source[$this->pos];
                switch ($ch) {
                    case '"':
                    case '\\':
                    case '/':
                        break;
                    case 'b':
                        $ch = sprintf("%c", 8);
                        break;
                    case 'f':
                        $ch = "\f";
                        break;
                    case 'n':
                        $ch = "\n";
                        break;
                    case 'r':
                        $ch = "\r";
                        break;
                    case 'u':
                        $codepoint = substr($this->source, $this->pos + 1, 4);
                        if (!preg_match('/[0-9A-Fa-f]{4}/', $codepoint)) {
                            throw $this->createException($this->getGraphQLErrorMessageProvider()->getInvalidStringUnicodeEscapeSequenceErrorMessage($codepoint));
                        }
                        $ch = html_entity_decode("&#x{$codepoint};", ENT_QUOTES, 'UTF-8');
                        $this->pos += 4;
                        break;
                    default:
                        throw $this->createException($this->getGraphQLErrorMessageProvider()->getUnexpectedStringEscapedCharacterErrorMessage($ch));
                }
            }

            $value .= $ch;
            $this->pos++;
        }

        throw $this->createUnexpectedTokenTypeException(Token::TYPE_END);
    }

    protected function end(): bool
    {
        return $this->lookAhead->getType() === Token::TYPE_END;
    }

    protected function peek(): Token
    {
        return $this->lookAhead;
    }

    protected function lex(): Token
    {
        $prev            = $this->lookAhead;
        $this->lookAhead = $this->next();

        return $prev;
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function createUnexpectedException(Token $token)
    {
        return $this->createUnexpectedTokenTypeException($token->getType());
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function createUnexpectedTokenTypeException($tokenType)
    {
        return $this->createException($this->getGraphQLErrorMessageProvider()->getUnexpectedTokenErrorMessage(Token::tokenName($tokenType)));
    }
}
