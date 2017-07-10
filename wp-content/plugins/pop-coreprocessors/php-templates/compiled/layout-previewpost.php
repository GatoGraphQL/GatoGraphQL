<?php
 function lcr595e1907b08ffwi($cx, $v, $bp, $in, $cb, $else = null) {
  if (isset($bp[0])) {
   $v = lcr595e1907b08ffm($cx, $v, array($bp[0] => $v));
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

 function lcr595e1907b08ffencq($cx, $var) {
  if ($var instanceof LS) {
   return (string)$var;
  }

  return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr595e1907b08ffraw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr595e1907b08ffsec($cx, $v, $bp, $in, $each, $cb, $else = null) {
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
     $raw = lcr595e1907b08ffm($cx, $raw, array($bp[0] => $raw));
    }
    if (isset($bp[1])) {
     $raw = lcr595e1907b08ffm($cx, $raw, array($bp[1] => $cx['sp_vars']['index']));
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

 function lcr595e1907b08ffhbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null) {
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
   } else if (isset($cx['blparam'][0])) {
    $ex = $cx['blparam'][0];
   }
   if (($context === '_NO_INPUT_HERE_') || ($context === $_this)) {
    $ret = $cb($cx, is_array($ex) ? lcr595e1907b08ffm($cx, $_this, $ex) : $_this);
   } else {
    $cx['scopes'][] = $_this;
    $ret = $cb($cx, is_array($ex) ? lcr595e1907b08ffm($cx, $context, $ex) : $context);
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

  return lcr595e1907b08ffexch($cx, $ch, $vars, $options);
 }

 function lcr595e1907b08ffhbch($cx, $ch, $vars, $op, &$_this) {
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

  return lcr595e1907b08ffexch($cx, $ch, $vars, $options);
 }

 function lcr595e1907b08ffraw($cx, $v, $ex = 0) {
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
      $ret[] = lcr595e1907b08ffraw($cx, $vv);
     }
     return join(',', $ret);
    }
   } else {
    return 'Array';
   }
  }

  return "$v";
 }

 function lcr595e1907b08ffifvar($cx, $v, $zero) {
  return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
 }

 function lcr595e1907b08ffm($cx, $a, $b) {
  if (is_array($b)) {
   if ($a === null) {
    return $b;
   } else if (is_array($a)) {
    return array_merge($a, $b);
   } else if (($cx['flags']['method'] || $cx['flags']['prop']) && is_object($a)) {
    foreach ($b as $i => $v) {
     $a->$i = $v;
    }
   }
  }
  return $a;
 }

 function lcr595e1907b08ffexch($cx, $ch, $vars, &$options) {
  $args = $vars[0];
  $args[] = $options;
  $e = null;
  $r = true;

  try {
   $r = call_user_func_array($cx['helpers'][$ch], $args);
  } catch (\Exception $E) {
   $e = "Runtime: call custom helper '$ch' error: " . $E->getMessage();
  }

  if($e !== null) {
   lcr595e1907b08fferr($cx, $e);
  }

  return $r;
 }

 function lcr595e1907b08fferr($cx, $err) {
  if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_LOG']) {
   error_log($err);
   return;
  }
  if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_EXCEPTION']) {
   throw new \Exception($err);
  }
 }

if (!class_exists("LS")) {
class LS {
 public static $jsContext = array (
  'flags' => 
  array (
    'jstrue' => 1,
    'jsobj' => 1,
  ),
);
    public function __construct($str, $escape = false) {
        $this->string = $escape ? (($escape === 'encq') ? static::encq(static::$jsContext, $str) : static::enc(static::$jsContext, $str)) : $str;
    }
    public function __toString() {
        return $this->string;
    }
    public static function stripExtendedComments($template) {
        return preg_replace(static::EXTENDED_COMMENT_SEARCH, '{{! }}', $template);
    }
    public static function escapeTemplate($template) {
        return addcslashes(addcslashes($template, '\\'), "'");
    }
    public static function raw($cx, $v, $ex = 0) {
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
    public static function enc($cx, $var) {
        return htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8');
    }
    public static function encq($cx, $var) {
        return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8'));
    }
}
}
return function ($in = null, $options = null) {
    $helpers = array(            'ondate' => function($date, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->ondate($date, $options);
	},
            'compare' => function($lvalue, $rvalue, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->compare($lvalue, $rvalue, $options);
	},
            'get' => function($db, $index, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->get($db, $index, $options);
	},
            'generateId' => function($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->generateId($options);
	},
            'enterModule' => function($prevContext, $options){ 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->enterModule($prevContext, $options);
	},
            'withModule' => function($context, $module, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withModule($context, $module, $options);
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
    ob_start();echo '',lcr595e1907b08ffwi($cx, (($inary && isset($in['itemObject'])) ? $in['itemObject'] : null), null, $in, function($cx, $in) {$inary=is_array($in);echo '	<div class="layout post-layout preview ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['class'])) ? $cx['scopes'][count($cx['scopes'])-1]['class'] : null)),' ',lcr595e1907b08ffsec($cx, (($inary && isset($in['cat-slugs'])) ? $in['cat-slugs'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo ' ',lcr595e1907b08ffencq($cx, $in),'';}),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['style'])) ? $cx['scopes'][count($cx['scopes'])-1]['style'] : null)),'" ',lcr595e1907b08ffhbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1])), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'';}),'>
',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'quicklinkgroup-top'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '			<div class="quicklinkgroup quicklinkgroup-top ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-top'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-top'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-top'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-top'] : null)),'">
				',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
			</div>
';}),'',lcr595e1907b08ffhbbch($cx, 'compare', array(array('abovethumb',((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['authors-position'])) ? $cx['scopes'][count($cx['scopes'])-1]['authors-position'] : null)),array('operator'=>'in')), $in, false, function($cx, $in) {$inary=is_array($in);echo '			<div class="authors abovethumb ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors'] : null)),' ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors-abovethumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors-abovethumb'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors'] : null)),'',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors-abovethumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors-abovethumb'] : null)),'">
				',lcr595e1907b08ffraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['abovethumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['abovethumb'] : null)),'
',lcr595e1907b08ffsec($cx, (($inary && isset($in['authors'])) ? $in['authors'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '					';if (lcr595e1907b08ffifvar($cx, (isset($cx['sp_vars']['index']) ? $cx['sp_vars']['index'] : null), false)){echo '',lcr595e1907b08ffraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['authors-sep'])) ? $cx['scopes'][count($cx['scopes'])-2]['authors-sep'] : null)),'';}else{echo '';}echo '
',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],'authors'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '						',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array('itemDBKey'=>((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]['bs']['db-keys']['subcomponents']['authors']) && isset($cx['scopes'][count($cx['scopes'])-3]['bs']['db-keys']['subcomponents']['authors']['db-key'])) ? $cx['scopes'][count($cx['scopes'])-3]['bs']['db-keys']['subcomponents']['authors']['db-key'] : null),'itemObjectId'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),'
';}),'';}),'				',lcr595e1907b08ffraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['abovethumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['abovethumb'] : null)),'
			</div>
';}),'		<div class="wrapper ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['wrapper'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['wrapper'] : null)),'">
			<div class="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['thumb-wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['thumb-wrapper'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['thumb-wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['thumb-wrapper'] : null)),'">
',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'postthumb'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '					<div class="post-thumb ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['thumb'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['thumb'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['thumb'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['thumb'] : null)),'">
						',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
					</div>
';}),'',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'author-avatar'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '					<div class="avatar ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['avatar'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['avatar'] : null)),'">					
						',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array('itemDBKey'=>((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['bs']['db-keys']['subcomponents']['authors']) && isset($cx['scopes'][count($cx['scopes'])-2]['bs']['db-keys']['subcomponents']['authors']['db-key'])) ? $cx['scopes'][count($cx['scopes'])-2]['bs']['db-keys']['subcomponents']['authors']['db-key'] : null),'itemObjectId'=>((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['authors'] : null))), 'encq', $in)),'
					</div>
';}),'';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowthumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowthumb'] : null), false)){echo '					<div class="extra ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['belowthumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['belowthumb'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['belowthumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['belowthumb'] : null)),'">
',lcr595e1907b08ffsec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowthumb'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowthumb'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '								<div class="extra-inner">
									',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
								</div>
';}),'';}),'					</div>
';}else{echo '';}echo '			</div>
';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['beforecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['beforecontent'] : null), false)){echo '				<div class="beforecontent ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['beforecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['beforecontent'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['beforecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['beforecontent'] : null)),'">
',lcr595e1907b08ffsec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['beforecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['beforecontent'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '						<div class="inner ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['beforecontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['beforecontent-inner'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['beforecontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['beforecontent-inner'] : null)),'">
',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '								',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';}),'						</div>
';}),'				</div>
';}else{echo '';}echo '			<div class="content-body ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['content-body'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['content-body'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['content-body'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['content-body'] : null)),'">
';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-date'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-date'] : null), false)){echo '					<a href="',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['url-field'])) ? $cx['scopes'][count($cx['scopes'])-1]['url-field'] : null)),array()), 'encq', $in)),'" title="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['date'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['date'] : null)),'" class="date ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['date'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['date'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['date'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['date'] : null)),'">',lcr595e1907b08ffraw($cx, lcr595e1907b08ffhbch($cx, 'ondate', array(array((($inary && isset($in['datetime'])) ? $in['datetime'] : null)),array()), 'raw', $in)),'</a>
';}else{echo '';}echo '',lcr595e1907b08ffhbbch($cx, 'compare', array(array('abovetitle',((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['authors-position'])) ? $cx['scopes'][count($cx['scopes'])-1]['authors-position'] : null)),array('operator'=>'in')), $in, false, function($cx, $in) {$inary=is_array($in);echo '					<div class="authors abovetitle ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors'] : null)),' ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors-abovetitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors-abovetitle'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors'] : null)),'',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors-abovetitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors-abovetitle'] : null)),'">
						',lcr595e1907b08ffraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['abovetitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['abovetitle'] : null)),'
',lcr595e1907b08ffsec($cx, (($inary && isset($in['authors'])) ? $in['authors'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '							';if (lcr595e1907b08ffifvar($cx, (isset($cx['sp_vars']['index']) ? $cx['sp_vars']['index'] : null), false)){echo '',lcr595e1907b08ffraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['authors-sep'])) ? $cx['scopes'][count($cx['scopes'])-2]['authors-sep'] : null)),'';}else{echo '';}echo '
',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],'authors'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '								',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array('itemDBKey'=>((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]['bs']['db-keys']['subcomponents']['authors']) && isset($cx['scopes'][count($cx['scopes'])-3]['bs']['db-keys']['subcomponents']['authors']['db-key'])) ? $cx['scopes'][count($cx['scopes'])-3]['bs']['db-keys']['subcomponents']['authors']['db-key'] : null),'itemObjectId'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),'
';}),'';}),'						',lcr595e1907b08ffraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['abovetitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['abovetitle'] : null)),'
					</div>
';}),'';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-posttitle'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-posttitle'] : null), false)){echo '					<',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'])) ? $cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'] : null)),' class="title ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['title'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['title'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['title'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['title'] : null)),'">
';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-date'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-date'] : null), false)){echo '							',lcr595e1907b08ffraw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'
';}else{echo '							<a href="',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['url-field'])) ? $cx['scopes'][count($cx['scopes'])-1]['url-field'] : null)),array()), 'encq', $in)),'" title="',lcr595e1907b08ffraw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'" target="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['link-target'])) ? $cx['scopes'][count($cx['scopes'])-1]['link-target'] : null)),'">',lcr595e1907b08ffraw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'</a>
';}echo '					</',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'])) ? $cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'] : null)),'>
';}else{echo '';}echo '';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['abovecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['abovecontent'] : null), false)){echo '					<div class="abovecontent ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['abovecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['abovecontent'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['abovecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['abovecontent'] : null)),'">
',lcr595e1907b08ffsec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['abovecontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['abovecontent'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '								',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';}),'';}),'					</div>
';}else{echo '';}echo '';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-excerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-excerpt'] : null), false)){echo '';if (lcr595e1907b08ffifvar($cx, (($inary && isset($in['excerpt'])) ? $in['excerpt'] : null), false)){echo '						<p class="excerpt ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['excerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['excerpt'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['excerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['excerpt'] : null)),'">',lcr595e1907b08ffraw($cx, (($inary && isset($in['excerpt'])) ? $in['excerpt'] : null)),'</p>
';}else{echo '';}echo '';}else{echo '';}echo '',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'content'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '					<div class="content pop-content ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['content'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['content'] : null)),' clearfix" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['content'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['content'] : null)),'" ',lcr595e1907b08ffhbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-2],'group'=>'content')), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['id'])) ? $cx['scopes'][count($cx['scopes'])-2]['id'] : null)),'-content';}),'>
						',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
					</div>
';}),'',lcr595e1907b08ffhbbch($cx, 'compare', array(array('belowcontent',((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['authors-position'])) ? $cx['scopes'][count($cx['scopes'])-1]['authors-position'] : null)),array('operator'=>'in')), $in, false, function($cx, $in) {$inary=is_array($in);echo '					<div class="authors belowcontent ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors'] : null)),' ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['authors-belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['authors-belowcontent'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors'] : null)),'',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['authors-belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['authors-belowcontent'] : null)),'">
						',lcr595e1907b08ffraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['beforeauthors']['belowcontent'] : null)),'
',lcr595e1907b08ffsec($cx, (($inary && isset($in['authors'])) ? $in['authors'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '							';if (lcr595e1907b08ffifvar($cx, (isset($cx['sp_vars']['index']) ? $cx['sp_vars']['index'] : null), false)){echo '',lcr595e1907b08ffraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]) && isset($cx['scopes'][count($cx['scopes'])-2]['authors-sep'])) ? $cx['scopes'][count($cx['scopes'])-2]['authors-sep'] : null)),'';}else{echo '';}echo '
',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],'authors'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '								',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array('itemDBKey'=>((isset($cx['scopes'][count($cx['scopes'])-3]) && is_array($cx['scopes'][count($cx['scopes'])-3]['bs']['db-keys']['subcomponents']['authors']) && isset($cx['scopes'][count($cx['scopes'])-3]['bs']['db-keys']['subcomponents']['authors']['db-key'])) ? $cx['scopes'][count($cx['scopes'])-3]['bs']['db-keys']['subcomponents']['authors']['db-key'] : null),'itemObjectId'=>$cx['scopes'][count($cx['scopes'])-1])), 'encq', $in)),'
';}),'';}),'						',lcr595e1907b08ffraw($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['afterauthors']['belowcontent'] : null)),'
					</div>
';}),'';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowcontent'] : null), false)){echo '					<div class="extra ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['belowcontent'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['belowcontent'] : null)),'">
',lcr595e1907b08ffsec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowcontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowcontent'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '							<div class="extra-inner ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['belowcontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['belowcontent-inner'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['belowcontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['belowcontent-inner'] : null)),'">
',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '									',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';}),'							</div>
';}),'					</div>
';}else{echo '';}echo '',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'quicklinkgroup-bottom'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '					<div class="quicklinkgroup quicklinkgroup-bottom ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-bottom'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-bottom'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-bottom'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['quicklinkgroup-bottom'] : null)),'">
						',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
					</div>
';}),'';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['bottom'] : null), false)){echo '					<div class="extra ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['bottom'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['bottom'] : null)),'">
',lcr595e1907b08ffsec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['bottom'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['bottom'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '							<div class="extra-inner ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['bottom-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['bottom-inner'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['bottom-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['bottom-inner'] : null)),'">
',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '									',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';}),'							</div>
';}),'					</div>
';}else{echo '';}echo '			</div>
';if (lcr595e1907b08ffifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['aftercontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['aftercontent'] : null), false)){echo '				<div class="aftercontent ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['aftercontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['aftercontent'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['styles']) && isset($cx['scopes'][count($cx['scopes'])-1]['styles']['aftercontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['styles']['aftercontent'] : null)),'">
',lcr595e1907b08ffsec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['aftercontent'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['aftercontent'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '						<div class="inner ',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['aftercontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['aftercontent-inner'] : null)),'" style="',lcr595e1907b08ffencq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['styles']) && isset($cx['scopes'][count($cx['scopes'])-2]['styles']['aftercontent-inner'])) ? $cx['scopes'][count($cx['scopes'])-2]['styles']['aftercontent-inner'] : null)),'">
',lcr595e1907b08ffhbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '								',lcr595e1907b08ffencq($cx, lcr595e1907b08ffhbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
';}),'						</div>
';}),'				</div>
';}else{echo '';}echo '		</div>
	</div>
';}),'';return ob_get_clean();
};
?>