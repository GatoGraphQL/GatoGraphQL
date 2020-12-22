<?php
 function lcr5bbdeb9672e4fwi($cx, $v, $bp, $in, $cb, $else = null)
 {
     if (isset($bp[0])) {
         $v = lcr5bbdeb9672e4fm($cx, $v, array($bp[0] => $v));
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

 function lcr5bbdeb9672e4fsec($cx, $v, $bp, $in, $each, $cb, $else = null)
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
                 $raw = lcr5bbdeb9672e4fm($cx, $raw, array($bp[0] => $raw));
             }
             if (isset($bp[1])) {
                 $raw = lcr5bbdeb9672e4fm($cx, $raw, array($bp[1] => $index));
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

 function lcr5bbdeb9672e4fencq($cx, $var)
 {
     if ($var instanceof LS) {
         return (string)$var;
     }

     return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdeb9672e4fraw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr5bbdeb9672e4fhbch($cx, $ch, $vars, $op, &$_this)
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

     return lcr5bbdeb9672e4fexch($cx, $ch, $vars, $options);
 }

 function lcr5bbdeb9672e4fm($cx, $a, $b)
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

 function lcr5bbdeb9672e4fraw($cx, $v, $ex = 0)
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
                     $ret[] = lcr5bbdeb9672e4fraw($cx, $vv);
                 }
                 return join(',', $ret);
             }
         } else {
             return 'Array';
         }
     }

     return "$v";
 }

 function lcr5bbdeb9672e4fexch($cx, $ch, $vars, &$options)
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
         lcr5bbdeb9672e4ferr($cx, $e);
     }

     return $r;
 }

 function lcr5bbdeb9672e4ferr($cx, $err)
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
    $helpers = array(            'get' => function ($db, $index, $options) {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->get($db, $index, $options);
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
    echo '',lcr5bbdeb9672e4fwi($cx, (($inary && isset($in['dbObject'])) ? $in['dbObject'] : null), null, $in, function ($cx, $in) {
        $inary=is_array($in);
        echo '	<script type="text/javascript">
	var targets, value, removeHidden;
	(function($){
',lcr5bbdeb9672e4fsec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['fields'])) ? $cx['scopes'][count($cx['scopes'])-1]['fields'] : null), null, $in, true, function ($cx, $in) {
            $inary=is_array($in);
            echo '			targets = $(\'.',lcr5bbdeb9672e4fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['dbKey'])) ? $cx['scopes'][count($cx['scopes'])-2]['dbKey'] : null)),'-',lcr5bbdeb9672e4fencq($cx, $in),'-',lcr5bbdeb9672e4fencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'\');
			value = \'',lcr5bbdeb9672e4fencq($cx, lcr5bbdeb9672e4fhbch($cx, 'get', array(array($cx['scopes'][count($cx['scopes'])-1],$in),array()), 'encq', $in)),'\';
			targets.html(value);

			removeHidden = targets.filter(\'.pop-show-notempty\');
			if (removeHidden.length && value && parseInt(value) > 0) {
				removeHidden.parents(\'.pop-show-notempty\').addBack(\'.pop-show-notempty\').removeClass(\'hidden\');
			}
';
        }
        ),'	})(jQuery);
	</script>	
';
    }),'';
    return ob_get_clean();
};
