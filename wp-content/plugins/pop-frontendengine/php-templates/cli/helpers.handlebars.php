<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-frontendengine/js/helpers.handlebars.js
*/
class PoP_ServerSide_HelperCallers {

	// public static function breaklines($text) { 

	// 	global $pop_serverside_helpers;
	// 	return $pop_serverside_helpers->breaklines($text);
	// }

	public static function showmore($str, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->showmore($str, $options);
	}

	public static function ondate($date, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->ondate($date, $options);
	}

	// public static function img($imageData, $options) { 

	// 	global $pop_serverside_helpers;
	// 	return $pop_serverside_helpers->img($imageData, $options);
	// }

	public static function addParam($url, $param, $value, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->addParam($url, $param, $value, $options);
	}

	public static function destroyUrl($url, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->destroyUrl($url, $options);
	}

	public static function statusLabel($status, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->statusLabel($status, $options);
	}

	public static function labelize($strings, $label, /*$clear,*/ $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->labelize($strings, $label, /*$clear,*/ $options);
	}

	// public static function infoButton($id, $itemObjectId, $options) { 

	// 	global $pop_serverside_helpers;
	// 	return $pop_serverside_helpers->infoButton($id, $itemObjectId, $options);
	// }

	// public static function infoCollapse($id, $itemObjectId, $options) { 

	// 	global $pop_serverside_helpers;
	// 	return $pop_serverside_helpers->infoCollapse($id, $itemObjectId, $options);
	// }

	public static function mod($lvalue, $rvalue, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->mod($lvalue, $rvalue, $options);
	}

	public static function compare($lvalue, $rvalue, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->compare($lvalue, $rvalue, $options);
	}

	public static function get($db, $index, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->get($db, $index, $options);
	}

	public static function ifget($db, $index, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->ifget($db, $index, $options);
	}

	public static function withget($db, $index, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withget($db, $index, $options);
	}

	public static function interceptAttr($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->interceptAttr($options);
	}

	public static function generateId($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->generateId($options);
	}

	public static function lastGeneratedId($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->lastGeneratedId($options);
	}

	public static function applyLightTemplate($template, $options){ 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->applyLightTemplate($template, $options);
	}

	// Taken from http://stackoverflow.com/questions/10232574/handlebars-js-parse-object-instead-of-object-object
	public static function json($context) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->json($context);
	}

	/* Comment Leo: taken from http://jsfiddle.net/dain/NRjUb/ */
	public static function enterModule($prevContext, $options){ 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->enterModule($prevContext, $options);
	}

	public static function getMultiLayoutKey($itemDBKey, $itemObject) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->getMultiLayoutKey($itemDBKey, $itemObject);
	}

	public static function withLayout($itemDBKey, $itemObjectId, $multipleLayouts, $context, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withLayout($itemDBKey, $itemObjectId, $multipleLayouts, $context, $options);
	}

	public static function layoutLabel($itemDBKey, $itemObject, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->layoutLabel($itemDBKey, $itemObject, $options);
	}

	// public static function withOptions($context, $options) { 

	// 	global $pop_serverside_helpers;
	// 	return $pop_serverside_helpers->withOptions($context, $options);
	// }

	public static function withBlock($context, $blockSettingsId, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withBlock($context, $blockSettingsId, $options);
	}

	public static function withModule($context, $module, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withModule($context, $module, $options);
	}

	public static function withSublevel($sublevel, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withSublevel($sublevel, $options);
	}

	public static function withItemObject($itemDBKey, $itemObjectId, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withItemObject($itemDBKey, $itemObjectId, $options);
	}

	// Taken from: https://github.com/raDiesle/Handlebars.js-helpers-collection#JOIN
	//function join($items, $block) { /*
	    //var delimiter = block.hash.delimiter || ",", 
	        //start = start = block.hash.start || 0, 
	        //len = items ? items.length : 0,
	        //end = block.hash.end || len,
	        //out = "";

	        //if(end > len) end = len;

	    //if ('function' === typeof block) {
	        //for (i = start; i < end; i++) {
	            //if (i > start) out += delimiter;
	            //if('string' === typeof items[i])
	                //out += items[i];
	            //else
	                //out += block(items[i]);
	        //}
	        //return out;
	    //} else { 
	        //return [].concat(items).slice(start, end).join(delimiter);
	    //}
	//*/}
}