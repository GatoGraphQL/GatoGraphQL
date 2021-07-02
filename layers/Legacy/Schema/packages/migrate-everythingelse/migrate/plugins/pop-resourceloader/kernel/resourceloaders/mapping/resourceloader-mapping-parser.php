<?php

class PoP_WebPlatform_ResourceLoaderMappingParser
{
    protected function _j_token_is_word(array $token)
    {
        $Lex = JLex::singleton();
        return is_array($token) && $Lex->is_word($token[1]);
    }

    protected function isPunctuation($token)
    {
        list($t, $s, $l, $c) = $token;
        return !is_int($t) && $s === $t;
    }

    protected function isLiteral($token)
    {
        list($t, $s, $l, $c) = $token;
        return j_token_name($t) == 'J_STRING_LITERAL';
    }

    public function extract(&$fileContents, $jsObjects = array())
    {
        @$tokens = j_token_get_all($fileContents);

        $internalMethodCalls = $externalMethodCalls = $publicMethods = $methodExecutions = array();
        $currentObject = $lastFunction = $lastSymbol = $lastPunctuation = '';
        $publicFunctionObject = null;
        $recordPublicFunctionObject = $recordPublicFunctions = $recordMethodExecution = false;

        // Must always add jsObject 'that', which stands for `this`
        $jsObjects[] = 'that';

        $inFunction = false;
        $objectsOpenInFunction = 1;
        for ($i = 0; $i < count($tokens); ++$i) {
            $token = $tokens[$i];
            list($t, $s, $l, $c) = $token;
            @$next = $tokens[$i+1];
            list($tn, $sn, $ln, $cn) = $next;
            @$prev = $tokens[$i-1];
            list($tp, $sp, $lp, $cp) = $prev;
            
            if ($this->_j_token_is_word($token)) {
                // Token is a keyword
                // If we are already inside of a function (eg: passing a function to $.ajax) then keep adding the info over the already open function, do not nest
                if (!$inFunction && $s == "function" && $lastPunctuation == ':') {
                    $inFunction = true;
                    $objectsOpenInFunction = 0; // Only works as long as we don't have nested functions
                    
                    $lastFunction = $lastSymbol;
                }
            } elseif ($this->isPunctuation($token)) {
                // If we were recording the public functions, and we hit "]", then stop recording
                if ($recordPublicFunctions && $s == ']') {
                    $recordPublicFunctions = false;
                }

                if ($s == "{") {
                    // Are we declaring the object? Check if it has the shape of: "pop.Manager = {"
                    if ($lastPunctuation == '=' && in_array($lastSymbol, $jsObjects)) {
                        $currentObject = $lastSymbol;
                    }

                    $objectsOpenInFunction += 1;
                } elseif ($s == "}") {
                    $objectsOpenInFunction -= 1;
                    if (!$inFunction || $objectsOpenInFunction != 0) {
                    } else {
                        $inFunction = false;
                    }
                }
                
                $lastPunctuation = $s;
            } elseif ($this->isLiteral($token) && ($recordPublicFunctions || $recordMethodExecution)) {
                // Remove the '' at the beginning and end
                $function = substr($s, 1, strlen($s)-2);
                if ($recordPublicFunctions) {
                    $publicMethods[$function][] = $publicFunctionObject;
                } else {
                    // $recordMethodExecution is true. Check that we're just calling a function name (a string literal), otherwise cancel the operation
                    // (popJSLibraryManager.execute('someFunction') must work but popJSLibraryManager.execute(functionName) must not)
                    if ($currentObject && $lastSymbol == 'execute') {
                        $methodExecutions[$currentObject][$lastFunction][] = $function;
                    }
                    $recordMethodExecution = false;
                }
            } else {
                // symbol
                if ($t == 2) {
                    // If we set to record the public functions Object, then save it now
                    // It will find objects of the type of "pop.Manager". "pop" is not the object, "Manager" is, so if this token is "pop" skip until the next one
                    if ($recordPublicFunctionObject && $s != 'pop') {
                        $publicFunctionObject = $s;

                        // Stop recording the object, and start recording the functions
                        $recordPublicFunctionObject = false;
                        $recordPublicFunctions = true;
                    }

                    $maybeFunctionCall = $s;

                    // We are parsing a function call, if the previous token is a . and the next is a (
                    // (someObject.callingFunction())
                    if ($currentObject && $lastFunction && $sp == '.' && $sn == '(' && in_array($lastSymbol, $jsObjects)/* && !in_array($lastSymbol, $ignoreObjects)*/) {
                        // Special case: register the public functions, from all those strings registered under object popJSLibraryManager
                        // If we are in this case, then do not save it under in/external method calls
                        if ($lastSymbol == 'JSLibraryManager') {
                            if ($maybeFunctionCall == 'register') {
                                $recordPublicFunctionObject = true;

                                // We left the current object
                                $currentObject = null;
                            } elseif ($maybeFunctionCall == 'execute') {
                                // Keep track of the pop.JSLibraryManager.execute calls inside the current function
                                $recordMethodExecution = true;
                            }
                        } else {
                            // Special case: if the last object is 'this' or 'that', then it's an internal method call. Or if the object being invoked is the same as the current. Otherwise it's an external one
                            if ($lastSymbol == 'this' || $lastSymbol == 'that' || $lastSymbol == $currentObject) {
                                $internalMethodCalls[$currentObject][$lastFunction] = $internalMethodCalls[$currentObject][$lastFunction] ?? array();
                                if (!in_array($maybeFunctionCall, $internalMethodCalls[$currentObject][$lastFunction])) {
                                    $internalMethodCalls[$currentObject][$lastFunction][] = $maybeFunctionCall;
                                }
                            } else {
                                $externalMethodCalls[$currentObject][$lastFunction][$lastSymbol] = $externalMethodCalls[$currentObject][$lastFunction][$lastSymbol] ?? array();
                                if (!in_array($maybeFunctionCall, $externalMethodCalls[$currentObject][$lastFunction][$lastSymbol])) {
                                    $externalMethodCalls[$currentObject][$lastFunction][$lastSymbol][] = $maybeFunctionCall;
                                }
                            }
                        }
                    }

                    $lastSymbol = $s;
                }
            }
        }

        return array(
            'publicMethods' => $publicMethods,
            'internalMethodCalls' => $internalMethodCalls,
            'externalMethodCalls' => $externalMethodCalls,
            'methodExecutions' => $methodExecutions,
        );
    }
}

/**
 * Initialization
 */
global $pop_webplatform_resourceloader_mappingparser;
$pop_webplatform_resourceloader_mappingparser = new PoP_WebPlatform_ResourceLoaderMappingParser();
