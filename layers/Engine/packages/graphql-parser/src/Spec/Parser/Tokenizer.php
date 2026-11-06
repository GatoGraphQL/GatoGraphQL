<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLParserErrorFeedbackItemProvider;
use PoP\Root\Services\StandaloneServiceTrait;

class Tokenizer
{
    use StandaloneServiceTrait;

    /**
     * The Unicode Byte Order Mark (U+FEFF) encoded in UTF-8,
     * which is ignored like whitespace
     *
     * @see https://spec.graphql.org/September2025/#UnicodeBOM
     */
    public final const UNICODE_BOM = "\xEF\xBB\xBF";

    protected string $source;
    protected int $pos = 0;
    protected int $line = 1;
    protected int $lineStart = 0;
    protected Token $lookAhead;

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
        $sourceLength = strlen($this->source);
        while ($this->pos < $sourceLength) {
            $ch = $this->source[$this->pos];
            if ($ch === ' ' || $ch === "\t" || $ch === ',') {
                $this->pos++;
            } elseif ($ch === self::UNICODE_BOM[0] && substr($this->source, $this->pos, 3) === self::UNICODE_BOM) {
                $this->pos += 3;
                $this->lineStart += 3;
            } elseif ($ch === '#') {
                $this->pos++;
                while (
                    $this->pos < $sourceLength &&
                    ($code = ord($this->source[$this->pos])) &&
                    // @phpstan-ignore-next-line
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
     * @throws SyntaxErrorParserException
     */
    protected function scan(): Token
    {
        $sourceLength = strlen($this->source);
        if ($this->pos >= $sourceLength) {
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


        if ($this->pos + 2 < $sourceLength) {
            $chars = substr($this->source, $this->pos, 3);
            if ($chars === '"""') {
                $this->pos += 2;
                return $this->scanString(true);
            }
        }

        if ($ch === '"') {
            return $this->scanString(false);
        }

        throw new SyntaxErrorParserException(
            new FeedbackItemResolution(
                GraphQLParserErrorFeedbackItemProvider::class,
                GraphQLParserErrorFeedbackItemProvider::E_5,
            ),
            $this->getLocation()
        );
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

        $sourceLength = strlen($this->source);
        while ($this->pos < $sourceLength) {
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
            'subscription' => Token::TYPE_SUBSCRIPTION,
            'on' => Token::TYPE_ON,
            default => Token::TYPE_IDENTIFIER,
        };
    }

    /**
     * @throws SyntaxErrorParserException
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
        $sourceLength = strlen($this->source);
        while ($this->pos < $sourceLength) {
            $ch = $this->source[$this->pos];
            if ('0' <= $ch && $ch <= '9') {
                $this->pos++;
            } else {
                break;
            }
        }
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
     * @throws SyntaxErrorParserException
     * @see http://facebook.github.io/graphql/October2016/#sec-String-Value
     */
    protected function scanString(bool $isBlockString): Token
    {
        $sourceLength = strlen($this->source);
        $this->pos++;

        $value = '';
        $blockStringNewLines = 0;
        $blockStringLineStart = $this->lineStart;
        while ($this->pos < $sourceLength) {
            $ch = $this->source[$this->pos];
            if ($isBlockString) {
                if ($this->pos + 2 < $sourceLength) {
                    $chars = substr($this->source, $this->pos, 3);
                    if ($chars === '"""') {
                        $token = new Token(Token::TYPE_BLOCK_STRING, $this->getLine(), $this->getColumn(), $value);

                        $this->line += $blockStringNewLines;
                        $this->lineStart = $blockStringLineStart;
                        $this->pos += 3;

                        return $token;
                    }
                }
            } else {
                if ($ch === '"') {
                    $token = new Token(Token::TYPE_STRING, $this->getLine(), $this->getColumn(), $value);
                    $this->pos++;

                    return $token;
                }
            }

            if ($ch === '\\' && ($this->pos < ($sourceLength - 1))) {
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
                    case 't':
                        $ch = "\t";
                        break;
                    case 'u':
                        $ch = $this->scanEscapedUnicode();
                        break;
                    default:
                        throw new SyntaxErrorParserException(
                            new FeedbackItemResolution(
                                GraphQLParserErrorFeedbackItemProvider::class,
                                GraphQLParserErrorFeedbackItemProvider::E_4,
                                [
                                    $ch,
                                ]
                            ),
                            $this->getLocation()
                        );
                }
            }

            $value .= $ch;
            $this->pos++;

            if ($ch === PHP_EOL && $isBlockString) {
                $blockStringNewLines++;
                $blockStringLineStart = $this->pos;
            }
        }

        throw $this->createUnexpectedTokenTypeException(Token::TYPE_END);
    }

    /**
     * Scan an escaped unicode sequence, in either form:
     *
     *   - Fixed-width: `A`, also as a surrogate pair (`😀`)
     *   - Braced: `\u{1F600}`
     *
     * The position must be on the "u" char, and is left on
     * the last char of the sequence.
     *
     * @throws SyntaxErrorParserException
     * @see https://spec.graphql.org/September2025/#EscapedUnicode
     */
    protected function scanEscapedUnicode(): string
    {
        $sequenceStartPos = $this->pos;
        [$codePoint, $isBraced] = $this->scanEscapedUnicodeCodePoint();

        if ($codePoint >= 0xD800 && $codePoint <= 0xDFFF) {
            $isLeadingSurrogate = $codePoint <= 0xDBFF;
            if ($isBraced || !$isLeadingSurrogate || substr($this->source, $this->pos + 1, 2) !== '\\u') {
                throw $this->createInvalidEscapedUnicodeException($sequenceStartPos, $this->pos);
            }
            $this->pos += 2;
            [$trailingCodePoint, $isTrailingBraced] = $this->scanEscapedUnicodeCodePoint();
            if ($isTrailingBraced || $trailingCodePoint < 0xDC00 || $trailingCodePoint > 0xDFFF) {
                throw $this->createInvalidEscapedUnicodeException($sequenceStartPos, $this->pos);
            }
            $codePoint = 0x10000 + (($codePoint - 0xD800) << 10) + ($trailingCodePoint - 0xDC00);
        }

        return $this->encodeCodePointAsUTF8($codePoint);
    }

    /**
     * @return array{0:int,1:bool} The code point, and if it is in the braced form
     * @throws SyntaxErrorParserException
     */
    protected function scanEscapedUnicodeCodePoint(): array
    {
        $sequenceStartPos = $this->pos;
        if (($this->source[$this->pos + 1] ?? '') === '{') {
            $closingBracePos = strpos($this->source, '}', $this->pos + 2);
            if ($closingBracePos === false) {
                throw $this->createInvalidEscapedUnicodeException($sequenceStartPos, $this->pos + strcspn($this->source, "\"\n\r", $this->pos + 1));
            }
            $hexDigits = substr($this->source, $this->pos + 2, $closingBracePos - $this->pos - 2);
            if (!preg_match('/^[0-9A-Fa-f]+$/', $hexDigits) || strlen(ltrim($hexDigits, '0')) > 6) {
                throw $this->createInvalidEscapedUnicodeException($sequenceStartPos, $closingBracePos);
            }
            $codePoint = (int) hexdec($hexDigits);
            if ($codePoint > 0x10FFFF) {
                throw $this->createInvalidEscapedUnicodeException($sequenceStartPos, $closingBracePos);
            }
            $this->pos = $closingBracePos;
            return [$codePoint, true];
        }

        $hexDigits = substr($this->source, $this->pos + 1, 4);
        if (!preg_match('/^[0-9A-Fa-f]{4}$/', $hexDigits)) {
            throw $this->createInvalidEscapedUnicodeException($sequenceStartPos, $this->pos + min(4, strcspn($this->source, "\"\n\r", $this->pos + 1)));
        }
        $this->pos += 4;
        return [(int) hexdec($hexDigits), false];
    }

    /**
     * @param int $sequenceStartPos Position of the "u" char
     * @param int $sequenceEndPos Position of the last char in the sequence
     */
    protected function createInvalidEscapedUnicodeException(int $sequenceStartPos, int $sequenceEndPos): SyntaxErrorParserException
    {
        $sequence = substr($this->source, $sequenceStartPos + 1, $sequenceEndPos - $sequenceStartPos);
        return new SyntaxErrorParserException(
            new FeedbackItemResolution(
                GraphQLParserErrorFeedbackItemProvider::class,
                GraphQLParserErrorFeedbackItemProvider::E_3,
                [
                    $sequence,
                ]
            ),
            $this->getLocation()
        );
    }

    protected function encodeCodePointAsUTF8(int $codePoint): string
    {
        if ($codePoint < 0x80) {
            return chr($codePoint);
        }
        if ($codePoint < 0x800) {
            return chr(0xC0 | ($codePoint >> 6))
                . chr(0x80 | ($codePoint & 0x3F));
        }
        if ($codePoint < 0x10000) {
            return chr(0xE0 | ($codePoint >> 12))
                . chr(0x80 | (($codePoint >> 6) & 0x3F))
                . chr(0x80 | ($codePoint & 0x3F));
        }
        return chr(0xF0 | ($codePoint >> 18))
            . chr(0x80 | (($codePoint >> 12) & 0x3F))
            . chr(0x80 | (($codePoint >> 6) & 0x3F))
            . chr(0x80 | ($codePoint & 0x3F));
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

    protected function createUnexpectedException(Token $token): SyntaxErrorParserException
    {
        return $this->createUnexpectedTokenTypeException($token->getType());
    }

    protected function createUnexpectedTokenTypeException(string $tokenType): SyntaxErrorParserException
    {
        return new SyntaxErrorParserException(
            new FeedbackItemResolution(
                GraphQLParserErrorFeedbackItemProvider::class,
                GraphQLParserErrorFeedbackItemProvider::E_6,
                [
                    Token::tokenName($tokenType),
                ]
            ),
            $this->getLocation()
        );
    }
}
