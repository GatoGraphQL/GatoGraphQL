<?php
 function lcr5bbdeb9a511a3wi($cx, $v, $bp, $in, $cb, $else = null)
 {
     if (isset($bp[0])) {
         $v = lcr5bbdeb9a511a3m($cx, $v, array($bp[0] => $v));
     }
     if (($v === false) || ($v === null) || (is_array($v) && (count($v) === 0))) {
         return $else ? $else($cx, $in) : '';
     }
     if ($v === $in) {
         $ret = $cb($cx, $v);
     } else {
         $cx['scopes'][] = $in;
         $ret = $cb($cx, $v);
         array_pop($cx['scopes']);
     }
     return $ret;
 }

 function lcr5bbdeb9a511a3encq($cx, $var)
 {
     if ($var instanceof LS) {
         return (string)$var;
     }

     return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdeb9a511a3raw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr5bbdeb9a511a3sec($cx, $v, $bp, $in, $each, $cb, $else = null)
 {
     $push = ($in !== $v) || $each;

     $isAry = is_array($v) || ($v instanceof \ArrayObject);
     $isTrav = $v instanceof \Traversable;
     $loop = $each;
     $keys = null;
     $last = null;
     $isObj = false;

     if ($isAry && $else !== null && count($v) === 0) {
         $ret = $else($cx, $in);
         return $ret;
     }

     // #var, detect input type is object or not
     if (!$loop && $isAry) {
         $keys = array_keys($v);
         $loop = (count(array_diff_key($v, array_keys($keys))) == 0);
         $isObj = !$loop;
     }

     if (($loop && $isAry) || $isTrav) {
         if ($each && !$isTrav) {
             // Detect input type is object or not when never done once
             if ($keys == null) {
                 $keys = array_keys($v);
                 $isObj = (count(array_diff_key($v, array_keys($keys))) > 0);
             }
         }
         $ret = array();
         if ($push) {
             $cx['scopes'][] = $in;
         }
         $i = 0;
         if ($cx['flags']['spvar']) {
             $old_spvar = $cx['sp_vars'];
             $cx['sp_vars'] = array_merge(array('root' => $old_spvar['root']), $old_spvar, array('_parent' => $old_spvar));
             if (!$isTrav) {
                 $last = count($keys) - 1;
             }
         }

         $isSparceArray = $isObj && (count(array_filter(array_keys($v), 'is_string')) == 0);
         foreach ($v as $index => $raw) {
             if ($cx['flags']['spvar']) {
                 $cx['sp_vars']['first'] = ($i === 0);
                 $cx['sp_vars']['last'] = ($i == $last);
                 $cx['sp_vars']['key'] = $index;
                 $cx['sp_vars']['index'] = $isSparceArray ? $index : $i;
                 $i++;
             }
             if (isset($bp[0])) {
                 $raw = lcr5bbdeb9a511a3m($cx, $raw, array($bp[0] => $raw));
             }
             if (isset($bp[1])) {
                 $raw = lcr5bbdeb9a511a3m($cx, $raw, array($bp[1] => $index));
             }
             $ret[] = $cb($cx, $raw);
         }
         if ($cx['flags']['spvar']) {
             if ($isObj) {
                 unset($cx['sp_vars']['key']);
             } else {
                 unset($cx['sp_vars']['last']);
             }
             unset($cx['sp_vars']['index']);
             unset($cx['sp_vars']['first']);
             $cx['sp_vars'] = $old_spvar;
         }
         if ($push) {
             array_pop($cx['scopes']);
         }
         return join('', $ret);
     }
     if ($each) {
         if ($else !== null) {
             $ret = $else($cx, $v);
             return $ret;
         }
         return '';
     }
     if ($isAry) {
         if ($push) {
             $cx['scopes'][] = $in;
         }
         $ret = $cb($cx, $v);
         if ($push) {
             array_pop($cx['scopes']);
         }
         return $ret;
     }

     if ($cx['flags']['mustsec']) {
         return $v ? $cb($cx, $in) : '';
     }

     if ($v === true) {
         return $cb($cx, $in);
     }

     if (($v !== null) && ($v !== false)) {
         return $cb($cx, $v);
     }

     if ($else !== null) {
         $ret = $else($cx, $in);
         return $ret;
     }

     return '';
 }

 function lcr5bbdeb9a511a3hbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null)
 {
     $options = array(
         'name' => $ch,
         'hash' => $vars[1],
         'contexts' => count($cx['scopes']) ? $cx['scopes'] : array(null),
         'fn.blockParams' => 0,
         '_this' => &$_this,
     );

     if ($cx['flags']['spvar']) {
         $options['data'] = $cx['sp_vars'];
     }

     if (isset($vars[2])) {
         $options['fn.blockParams'] = count($vars[2]);
     }

     // $invert the logic
     if ($inverted) {
         $tmp = $else;
         $else = $cb;
         $cb = $tmp;
     }

     $options['fn'] = function ($context = '_NO_INPUT_HERE_', $data = null) use ($cx, &$_this, $cb, $options, $vars) {
         if ($cx['flags']['echo']) {
             ob_start();
         }
         if (isset($data['data'])) {
             $old_spvar = $cx['sp_vars'];
             $cx['sp_vars'] = array_merge(array('root' => $old_spvar['root']), $data['data'], array('_parent' => $old_spvar));
         }
         $ex = false;
         if (isset($data['blockParams']) && isset($vars[2])) {
             $ex = array_combine($vars[2], array_slice($data['blockParams'], 0, count($vars[2])));
             array_unshift($cx['blparam'], $ex);
         } elseif (isset($cx['blparam'][0])) {
             $ex = $cx['blparam'][0];
         }
         if (($context === '_NO_INPUT_HERE_') || ($context === $_this)) {
             $ret = $cb($cx, is_array($ex) ? lcr5bbdeb9a511a3m($cx, $_this, $ex) : $_this);
         } else {
             $cx['scopes'][] = $_this;
             $ret = $cb($cx, is_array($ex) ? lcr5bbdeb9a511a3m($cx, $context, $ex) : $context);
             array_pop($cx['scopes']);
         }
         if (isset($data['data'])) {
             $cx['sp_vars'] = $old_spvar;
         }
         return $cx['flags']['echo'] ? ob_get_clean() : $ret;
     };

     if ($else) {
         $options['inverse'] = function ($context = '_NO_INPUT_HERE_') use ($cx, $_this, $else) {
             if ($cx['flags']['echo']) {
                 ob_start();
             }
             if ($context === '_NO_INPUT_HERE_') {
                 $ret = $else($cx, $_this);
             } else {
                 $cx['scopes'][] = $_this;
                 $ret = $else($cx, $context);
                 array_pop($cx['scopes']);
             }
             return $cx['flags']['echo'] ? ob_get_clean() : $ret;
         };
     } else {
         $options['inverse'] = function () {
             return '';
         };
     }

     return lcr5bbdeb9a511a3exch($cx, $ch, $vars, $options);
 }

 function lcr5bbdeb9a511a3hbch($cx, $ch, $vars, $op, &$_this)
 {
     if (isset($cx['blparam'][0][$ch])) {
         return $cx['blparam'][0][$ch];
     }

     $options = array(
         'name' => $ch,
         'hash' => $vars[1],
         'contexts' => count($cx['scopes']) ? $cx['scopes'] : array(null),
         'fn.blockParams' => 0,
         '_this' => &$_this
     );

     if ($cx['flags']['spvar']) {
         $options['data'] = $cx['sp_vars'];
     }

     return lcr5bbdeb9a511a3exch($cx, $ch, $vars, $options);
 }

 function lcr5bbdeb9a511a3ifvar($cx, $v, $zero)
 {
     return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
 }

 function lcr5bbdeb9a511a3raw($cx, $v, $ex = 0)
 {
     if ($ex) {
         return $v;
     }

     if ($v === true) {
         if ($cx['flags']['jstrue']) {
             return 'true';
         }
     }

     if (($v === false)) {
         if ($cx['flags']['jstrue']) {
             return 'false';
         }
     }

     if (is_array($v)) {
         if ($cx['flags']['jsobj']) {
             if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
                 return '[object Object]';
             } else {
                 $ret = array();
                 foreach ($v as $k => $vv) {
                     $ret[] = lcr5bbdeb9a511a3raw($cx, $vv);
                 }
                 return join(',', $ret);
             }
         } else {
             return 'Array';
         }
     }

     return "$v";
 }

 function lcr5bbdeb9a511a3m($cx, $a, $b)
 {
     if (is_array($b)) {
         if ($a === null) {
             return $b;
         } elseif (is_array($a)) {
             return array_merge($a, $b);
         } elseif ($cx['flags']['method'] || $cx['flags']['prop']) {
             if (!is_object($a)) {
                 $a = new StringObject($a);
             }
             foreach ($b as $i => $v) {
                 $a->$i = $v;
             }
         }
     }
     return $a;
 }

 function lcr5bbdeb9a511a3exch($cx, $ch, $vars, &$options)
 {
     $args = $vars[0];
     $args[] = $options;
     $e = null;
     $r = true;

     try {
         $r = call_user_func_array($cx['helpers'][$ch], $args);
     } catch (\Exception $E) {
         $e = "Runtime: call custom helper '$ch' error: " . $E->getMessage();
     }

     if ($e !== null) {
         lcr5bbdeb9a511a3err($cx, $e);
     }

     return $r;
 }

 function lcr5bbdeb9a511a3err($cx, $err)
 {
     if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_LOG']) {
         error_log($err);
         return;
     }
     if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_EXCEPTION']) {
         throw new \Exception($err);
     }
 }

if (!class_exists("LS")) {
    class LS
    {
        public static $jsContext = array(
            'flags' =>
            array(
                'jstrue' => 1,
                'jsobj' => 1,
            ),
        );
        public function __construct($str, $escape = false)
        {
            $this->string = $escape ? (($escape === 'encq') ? static::encq(static::$jsContext, $str) : static::enc(static::$jsContext, $str)) : $str;
        }
        public function tostring()
        {
            return $this->string;
        }
        public static function stripExtendedComments($template)
        {
            return preg_replace(static::EXTENDED_COMMENT_SEARCH, '{{! }}', $template);
        }
        public static function escapeTemplate($template)
        {
            return addcslashes(addcslashes($template, '\\'), "'");
        }
        public static function raw($cx, $v, $ex = 0)
        {
            if ($ex) {
                return $v;
            }

            if ($v === true) {
                if ($cx['flags']['jstrue']) {
                    return 'true';
                }
            }

            if (($v === false)) {
                if ($cx['flags']['jstrue']) {
                    return 'false';
                }
            }

            if (is_array($v)) {
                if ($cx['flags']['jsobj']) {
                    if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
                        return '[object Object]';
                    } else {
                        $ret = array();
                        foreach ($v as $k => $vv) {
                            $ret[] = static::raw($cx, $vv);
                        }
                        return join(',', $ret);
                    }
                } else {
                    return 'Array';
                }
            }

            return "$v";
        }
        public static function enc($cx, $var)
        {
            return htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8');
        }
        public static function encq($cx, $var)
        {
            return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8'));
        }
    }
}
return function ($in = null, $options = null) {
    $helpers = array(            'generateId' => function ($options) {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->generateId($options);
    },
        'enterModule' => function ($prevContext, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->enterModule($prevContext, $options);
        },
        'withModule' => function ($context, $moduleName, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->withModule($context, $moduleName, $options);
        },
        'get' => function ($db, $index, $options) {
            global $pop_serverside_kernelhelpers;
            return $pop_serverside_kernelhelpers->get($db, $index, $options);
        },
        'compare' => function ($lvalue, $rvalue, $options) {
            global $pop_serverside_comparehelpers;
            return $pop_serverside_comparehelpers->compare($lvalue, $rvalue, $options);
        },
        'ondate' => function ($date, $options) {
            global $pop_serverside_datehelpers;
            return $pop_serverside_datehelpers->ondate($date, $options);
        },
    );
    $partials = array();
    $cx = array(
        'flags' => array(
            'jstrue' => true,
            'jsobj' => true,
            'jslen' => true,
            'spvar' => true,
            'prop' => false,
            'method' => false,
            'lambda' => false,
            'mustlok' => false,
            'mustlam' => false,
            'mustsec' => false,
            'echo' => true,
            'partnc' => false,
            'knohlp' => false,
            'debug' => isset($options['debug']) ? $options['debug'] : 1,
        ),
        'constants' =>  array(
            'DEBUG_ERROR_LOG' => 1,
            'DEBUG_ERROR_EXCEPTION' => 2,
            'DEBUG_TAGS' => 4,
            'DEBUG_TAGS_ANSI' => 12,
            'DEBUG_TAGS_HTML' => 20,
        ),
        'helpers' => isset($options['helpers']) ? array_merge($helpers, $options['helpers']) : $helpers,
        'partials' => isset($options['partials']) ? array_merge($partials, $options['partials']) : $partials,
        'scopes' => array(),
        'sp_vars' => isset($options['data']) ? array_merge(array('root' => $in), $options['data']) : array('root' => $in),
        'blparam' => array(),
        'partialid' => 0,
        'runtime' => '\LightnCandy\Runtime',
    );
    
    $inary=is_array($in);
    ob_start();
    echo '',lcr5bbdeb9a511a3wi($cx, (($inary && isset($in['dbObject'])) ? $in['dbObject'] : null), null, $in, function ($cx, $in) {
        $inary=is_array($in);
        echo '	<div class="layout post-layout preview ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['class'])) ? $cx['scopes'][count($cx['scopes'])-1]['class'] : null)),' ',lcr5bbdeb9a511a3encq($cx, (($inary && isset($in['post-type'])) ? $in['post-type'] : null)),' ',lcr5bbdeb9a511a3sec($cx, (($inary && isset($in['cat-slugs'])) ? $in['cat-slugs'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo ' ',lcr5bbdeb9a511a3encq($cx, $in),'';
        }),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['style'])) ? $cx['scopes'][count($cx['scopes'])-1]['style'] : null)),'" ',lcr5bbdeb9a511a3hbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1])), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'';
        }),'>
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'quicklinkgroup-top'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '			<div class="quicklinkgroup quicklinkgroup-top ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-top'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-top'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-top'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-top'] : null)),'">
				',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
			</div>
';
        }),'';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['top'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['top'] : null), false)) {
            echo '			<div class="top ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['top'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['top'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['top'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['top'] : null)),'">
',lcr5bbdeb9a511a3sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['top'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['top'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '					<div class="inner ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['top-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['top-inner'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['top-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['top-inner'] : null)),'">
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '							',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';
                }
                ),'					</div>
';
            }
            ),'			</div>
';
        } else {
            echo '';
        }
        echo '',lcr5bbdeb9a511a3hbbch($cx, 'compare', array(array('abovethumb',((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['authors-position'])) ? $cx['scopes'][count($cx['scopes'])-1]['authors-position'] : null)),array('operator'=>'in')), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '			<div class="authors abovethumb ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors'] : null)),' ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors-abovethumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors-abovethumb'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors'] : null)),'',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors-abovethumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors-abovethumb'] : null)),'">
				',lcr5bbdeb9a511a3raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['abovethumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['abovethumb'] : null)),'
',lcr5bbdeb9a511a3sec($cx, (($inary && isset($in['authors'])) ? $in['authors'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '					';
                if (lcr5bbdeb9a511a3ifvar($cx, (isset($cx['sp_vars']['index']) ? $cx['sp_vars']['index'] : null), false)) {
                    echo '',lcr5bbdeb9a511a3raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['authors-sep'])) ? $cx['scopes'][count($cx['scopes'])-2]['authors-sep'] : null)),'';
                } else {
                    echo '';
                }
                echo '
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],'authors'),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '						',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array('dbKey'=>((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]['bs']['dbkeys']) && isset($cx['scopes'][count($cx['scopes'])-3]['bs']['dbkeys']['authors'])) ? $cx['scopes'][count($cx['scopes'])-3]['bs']['dbkeys']['authors'] : null),'dbObjectID'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),'
';
                }
                ),'';
            }
            ),'				',lcr5bbdeb9a511a3raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['abovethumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['abovethumb'] : null)),'
			</div>
';
        }),'		<div class="wrapper ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['wrapper'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['wrapper'] : null)),'">
			<div class="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['thumb-wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['thumb-wrapper'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['thumb-wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['thumb-wrapper'] : null)),'">
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'postthumb'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '					<div class="post-thumb ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['thumb'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['thumb'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['thumb'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['thumb'] : null)),'">
						',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
					</div>
';
        }),'',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'author-avatar'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '					<div class="avatar ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['avatar'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['avatar'] : null)),'">					
						',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array('dbKey'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['bs']['dbkeys']) && isset($cx['scopes'][count($cx['scopes'])-2]['bs']['dbkeys']['authors'])) ? $cx['scopes'][count($cx['scopes'])-2]['bs']['dbkeys']['authors'] : null),'dbObjectID'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['authors'] : null))), 'encq', $in)),'
					</div>
';
        }),'';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['belowthumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['belowthumb'] : null), false)) {
            echo '					<div class="extra ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['belowthumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['belowthumb'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['belowthumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['belowthumb'] : null)),'">
',lcr5bbdeb9a511a3sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['belowthumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['belowthumb'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '								<div class="extra-inner">
									',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
								</div>
';
                }
                ),'';
            }
            ),'					</div>
';
        } else {
            echo '';
        }
        echo '			</div>
';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['beforecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['beforecontent'] : null), false)) {
            echo '				<div class="beforecontent ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['beforecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['beforecontent'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['beforecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['beforecontent'] : null)),'">
',lcr5bbdeb9a511a3sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['beforecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['beforecontent'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '						<div class="inner ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['beforecontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['beforecontent-inner'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['beforecontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['beforecontent-inner'] : null)),'">
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '								',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';
                }
                ),'						</div>
';
            }
            ),'				</div>
';
        } else {
            echo '';
        }
        echo '			<div class="content-body ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['content-body'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['content-body'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['content-body'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['content-body'] : null)),'">
';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-date'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-date'] : null), false)) {
            echo '					<a href="',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['url-field'])) ? $cx['scopes'][count($cx['scopes'])-1]['url-field'] : null)),array()), 'encq', $in)),'" title="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['date'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['date'] : null)),'" class="date ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['date'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['date'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['date'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['date'] : null)),'">',lcr5bbdeb9a511a3raw($cx, lcr5bbdeb9a511a3hbch($cx, 'ondate', array(array((($inary && isset($in['datetime'])) ? $in['datetime'] : null)),array()), 'raw', $in)),'</a>
';
        } else {
            echo '';
        }
        echo '',lcr5bbdeb9a511a3hbbch($cx, 'compare', array(array('abovetitle',((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['authors-position'])) ? $cx['scopes'][count($cx['scopes'])-1]['authors-position'] : null)),array('operator'=>'in')), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '					<div class="authors abovetitle ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors'] : null)),' ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors-abovetitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors-abovetitle'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors'] : null)),'',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors-abovetitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors-abovetitle'] : null)),'">
						',lcr5bbdeb9a511a3raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['abovetitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['abovetitle'] : null)),'
',lcr5bbdeb9a511a3sec($cx, (($inary && isset($in['authors'])) ? $in['authors'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '							';
                if (lcr5bbdeb9a511a3ifvar($cx, (isset($cx['sp_vars']['index']) ? $cx['sp_vars']['index'] : null), false)) {
                    echo '',lcr5bbdeb9a511a3raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['authors-sep'])) ? $cx['scopes'][count($cx['scopes'])-2]['authors-sep'] : null)),'';
                } else {
                    echo '';
                }
                echo '
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],'authors'),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '								',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array('dbKey'=>((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]['bs']['dbkeys']) && isset($cx['scopes'][count($cx['scopes'])-3]['bs']['dbkeys']['authors'])) ? $cx['scopes'][count($cx['scopes'])-3]['bs']['dbkeys']['authors'] : null),'dbObjectID'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),'
';
                }
                ),'';
            }
            ),'						',lcr5bbdeb9a511a3raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['abovetitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['abovetitle'] : null)),'
					</div>
';
        }),'';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-posttitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-posttitle'] : null), false)) {
            echo '					<',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'])) ? $cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'] : null)),' class="title ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['title'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['title'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['title'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['title'] : null)),'">
';
            if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-date'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-date'] : null), false)) {
                echo '							',lcr5bbdeb9a511a3raw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'
';
            } else {
                echo '							<a href="',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['url-field'])) ? $cx['scopes'][count($cx['scopes'])-1]['url-field'] : null)),array()), 'encq', $in)),'" title="',lcr5bbdeb9a511a3raw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'" target="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['link-target'])) ? $cx['scopes'][count($cx['scopes'])-1]['link-target'] : null)),'">',lcr5bbdeb9a511a3raw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'</a>
';
            }
            echo '					</',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'])) ? $cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'] : null)),'>
';
        } else {
            echo '';
        }
        echo '';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['abovecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['abovecontent'] : null), false)) {
            echo '					<div class="abovecontent ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['abovecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['abovecontent'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['abovecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['abovecontent'] : null)),'">
',lcr5bbdeb9a511a3sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['abovecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['abovecontent'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '								',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';
                }
                ),'';
            }
            ),'					</div>
';
        } else {
            echo '';
        }
        echo '';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-excerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-excerpt'] : null), false)) {
            echo '';
            if (lcr5bbdeb9a511a3ifvar($cx, (($inary && isset($in['excerpt'])) ? $in['excerpt'] : null), false)) {
                echo '						<p class="excerpt ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['excerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['excerpt'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['excerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['excerpt'] : null)),'">',lcr5bbdeb9a511a3raw($cx, (($inary && isset($in['excerpt'])) ? $in['excerpt'] : null)),'</p>
';
            } else {
                echo '';
            }
            echo '';
        } else {
            echo '';
        }
        echo '',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'content'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '					<div class="content pop-content ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['content'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['content'] : null)),' clearfix" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['content'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['content'] : null)),'" ',lcr5bbdeb9a511a3hbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>'content')), $in, false, function ($cx, $in) {
                $inary=is_array($in);
                echo '',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['id'])) ? $cx['scopes'][count($cx['scopes'])-2]['id'] : null)),'-content';
            }
            ),'>
						',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
					</div>
';
        }),'',lcr5bbdeb9a511a3hbbch($cx, 'compare', array(array('belowcontent',((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['authors-position'])) ? $cx['scopes'][count($cx['scopes'])-1]['authors-position'] : null)),array('operator'=>'in')), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '					<div class="authors belowcontent ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors'] : null)),' ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors-belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors-belowcontent'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors'] : null)),'',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors-belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors-belowcontent'] : null)),'">
						',lcr5bbdeb9a511a3raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['belowcontent'] : null)),'
',lcr5bbdeb9a511a3sec($cx, (($inary && isset($in['authors'])) ? $in['authors'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '							';
                if (lcr5bbdeb9a511a3ifvar($cx, (isset($cx['sp_vars']['index']) ? $cx['sp_vars']['index'] : null), false)) {
                    echo '',lcr5bbdeb9a511a3raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['authors-sep'])) ? $cx['scopes'][count($cx['scopes'])-2]['authors-sep'] : null)),'';
                } else {
                    echo '';
                }
                echo '
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],'authors'),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '								',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array('dbKey'=>((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]['bs']['dbkeys']) && isset($cx['scopes'][count($cx['scopes'])-3]['bs']['dbkeys']['authors'])) ? $cx['scopes'][count($cx['scopes'])-3]['bs']['dbkeys']['authors'] : null),'dbObjectID'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),'
';
                }
                ),'';
            }
            ),'						',lcr5bbdeb9a511a3raw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['belowcontent'] : null)),'
					</div>
';
        }),'';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['belowcontent'] : null), false)) {
            echo '					<div class="extra ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['belowcontent'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['belowcontent'] : null)),'">
',lcr5bbdeb9a511a3sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['belowcontent'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '							<div class="extra-inner ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['belowcontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['belowcontent-inner'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['belowcontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['belowcontent-inner'] : null)),'">
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '									',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';
                }
                ),'							</div>
';
            }
            ),'					</div>
';
        } else {
            echo '';
        }
        echo '',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'quicklinkgroup-bottom'),array()), $in, false, function ($cx, $in) {
            $inary=is_array($in);
            echo '					<div class="quicklinkgroup quicklinkgroup-bottom ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-bottom'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-bottom'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-bottom'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-bottom'] : null)),'">
						',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
					</div>
';
        }),'';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['bottom'] : null), false)) {
            echo '					<div class="extra ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['bottom'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['bottom'] : null)),'">
',lcr5bbdeb9a511a3sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['bottom'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '							<div class="extra-inner ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['bottom-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['bottom-inner'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['bottom-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['bottom-inner'] : null)),'">
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '									',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';
                }
                ),'							</div>
';
            }
            ),'					</div>
';
        } else {
            echo '';
        }
        echo '			</div>
';
        if (lcr5bbdeb9a511a3ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['aftercontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['aftercontent'] : null), false)) {
            echo '				<div class="aftercontent ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['aftercontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['aftercontent'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['aftercontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['aftercontent'] : null)),'">
',lcr5bbdeb9a511a3sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['module-names']) && isset($cx['scopes'][count($cx['scopes'])-1]['module-names']['aftercontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['module-names']['aftercontent'] : null), null, $in, true, function ($cx, $in) {
                $inary=is_array($in);
                echo '						<div class="inner ',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['aftercontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['aftercontent-inner'] : null)),'" style="',lcr5bbdeb9a511a3encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['aftercontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['aftercontent-inner'] : null)),'">
',lcr5bbdeb9a511a3hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function ($cx, $in) {
                    $inary=is_array($in);
                    echo '								',lcr5bbdeb9a511a3encq($cx, lcr5bbdeb9a511a3hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';
                }
                ),'						</div>
';
            }
            ),'				</div>
';
        } else {
            echo '';
        }
        echo '		</div>
	</div>
';
    }),'';
    return ob_get_clean();
};
