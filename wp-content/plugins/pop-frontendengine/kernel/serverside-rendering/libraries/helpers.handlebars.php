<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-frontendengine/js/helpers.handlebars.js
*/
class PoP_ServerSide_Helpers {

	// function breaklines($text) {
	//     $text = esc_html($text);
	//     $text = str_replace(array("\r\n","\n","\r"), '<br>', $text);
	//     return new LS($text);
	// }

	function showmore($str, $options) {

	    // len == 0 => No need for showmore
	    $len = $options['hash']['len'] ?? 0;

	    // Only if at least 100 chars more, so that it doesn't shorten just a tiny bit of text
	    if ($len > 0 && strlen($str) > $len + 100) {

			// If we find "</p>", then we must also hide the bit until that </p>
			$delim = "</p>";
			$total_len = $len;
			$morelink = '<a href="#" class="pop-showmore-more">'.GD_STRING_MORE.'</a>';
			$lesslink = '<a href="#" class="pop-showmore-less hidden">'.GD_STRING_LESS.'</a>';
			$moreless = false;
			$add_morelink = true;
			if ((strlen($str) > $total_len) && (strpos(substr($str, $len), $delim) > -1)) {

				// Add the moreless links at the end, if only to show the hidden text inside the <p></p>
				$moreless = true;
				$add_morelink = false;
				
				// Wrap excess characters inside the <p></p> inside a hidden span
				// Add the morelink inside the <p></p> so it shows inline
				$str = 
					substr($str, 0, $len).
					'<span class="pop-showmore-more-full hidden">'.
					substr($str, $len, strpos(substr($str, $len), $delim)).
					'</span> '.
					$morelink.
					substr($str, $len+strpos(substr($str, $len), $delim));

				$total_len = $len + strpos(substr($str, $len), $delim) + strlen($delim);
			}

			if ($moreless || (strlen($str) > $total_len)) {
				
				// Make sure there still some string left after the operation. If not, then nothing to hide
				$str_end = substr($str, $total_len);
				$has_endstr = strlen(trim($str_end));
				if ($moreless || $has_endstr) {
					$str_beg = substr($str, 0, $total_len);
					$str_new = 	
						'<span class="pop-showmore-more-teaser">'.$str_beg.'</span>'. 
						($has_endstr ? '<span class="pop-showmore-more-full hidden">'.$str_end.'</span> ' : ' ').
						($add_morelink ? $morelink : '').
						$lesslink;
			        return new LS($str_new);     
			    }
		    }
	    }
	    
	    return $str;
	}

	function ondate($date, $options) {

	    return new LS(sprintf(PoP_Frontend_ConfigurationUtils::get_ondate_string(), $date));     
	}

	// function img($imageData, $options) {

	//     $url = $options['hash']['url'];
 //    	$title = $options['hash']['title'] ?? "";
 //    	$alt = $options['hash']['alt'] ?? "";
 //    	$classs = $options['hash']['class'] ?? "";
 //    	$ret = "";
	        
	// 	if ($url) { 
	// 		$ret .= '<a href="'.$url.'" title="'.$title.'">'; 
	// 	}
	// 	$ret .= '<img src="'.$imageData['src'].'" width="'.$imageData['width'].'" height="'.$imageData['height'].'" alt="'.$alt.'" class="'.$classs.'">';
	// 	if ($url) { 
	// 		$ret .= '</a>'; 
	// 	}

	//     return new LS($ret);
	// }

	function addParam($url, $param, $value, $options) {

		// Allow for inputs with multiple values
		if (!is_array($value)) {
			$value = array($value);
		}
		foreach($value as $val) {

			$url = add_query_arg($param, $val, $url);
		}

		return $url;
	}

	function destroyUrl($url, $options) {

		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		return new LS($popManager->getDestroyUrl($url)/*, 'encq'*/);
	}

	function statusLabel($status, $options) {

		$statusSettings = PoP_Frontend_ConfigurationUtils::get_status_settings();
		$ret = '<span class="label '.$statusSettings['class'][$status].' label-'.$status.'">'.$statusSettings['text'][$status].'</span>';

		return new LS($ret);
	}

	function labelize($strings, $label, /*$clear,*/ $options) {

		$labelize_classes = PoP_Frontend_ConfigurationUtils::get_labelize_classes();
		$ret = '';
		$extra_class = '';
		if ($strings) {
			for ($i = 0; $i < count($strings); $i++) {
				$extra_class = $labelize_classes[$strings[$i]] ?? '';
				$ret .= '<span class="label '.$label.' '.$extra_class.'">'.$strings[$i].'</span> ';
			}
		}

		return new LS($ret);
	}

	// function infoButton($id, $itemObjectId, $options) {

	// 	$classs = $options['hash']['classs'] ?? '';
	// 	$ret = '<a class="'.$classs.'" data-toggle="collapse" href="#'.$id.'-'.$itemObjectId.'" role="button"><span class="glyphicon glyphicon-info-sign"></span></a>';
	// 	return new LS($ret);
	// }

	// function infoCollapse($id, $itemObjectId, $options) {

	// 	$content = $options['fn']();
	// 	$ret = '<div class="collapse" id="'.$id.'-'.$itemObjectId.'">'.$content.'</div>';
	// 	return new LS($ret);
	// }

	function mod($lvalue, $rvalue, $options) {
	    // Comment Leo: Not needed in PHP => Commented out
	    // if (count($arguments) < 3) {
	    //     throw new Exception("Handlebars Helper equal needs 2 parameters");
	    // }
	        
	    $offset = $options['hash']['offset'] ?? 0;
	            
	    if( ($lvalue + $offset) % $rvalue === 0 ) {
	        return $options['fn']();
	    } else {
	        return $options['inverse']();
	    }
	}

	function eq($lvalue, $rvalue) {

	    return $lvalue === $rvalue;
	}

	function or($lvalue, $rvalue) {

	    return $lvalue || $rvalue;
	}

	function compare($lvalue, $rvalue, $options) {

	    // Comment Leo: Not needed in PHP => Commented out
	    // if (count($arguments) < 3) {
	    //     throw new Exception("Handlerbars Helper 'compare' needs 2 parameters");
	    // }

	    $operator = $options['hash']['operator'] ?? "==";

	    $operators = array(
	        // function eq: allows to compare a string against a bool, then "true" == true (eg: for the Yes/No Select) return "// function eq: allows to compare a string against a bool, then "true" == true (eg: for the Yes/No Selec"; /*
	        'eq' =>       function($l,$r) { return $l == $r || (string)$l == (string)$r; },
	        '==' =>       function($l,$r) { return $l == $r; },
	        '===' =>      function($l,$r) { return $l === $r; },
	        '!=' =>       function($l,$r) { return $l != $r; },
	        '<' =>        function($l,$r) { return $l < $r; },
	        '>' =>        function($l,$r) { return $l > $r; },
	        '<=' =>       function($l,$r) { return $l <= $r; },
	        '>=' =>       function($l,$r) { return $l >= $r; },
	        'typeof' =>   function($l,$r) { return gettype($l) == $r; },
	        'in' =>   	function($l,$r) { return $r && array_search($l, $r) !== false; },
	        'notin' =>   	function($l,$r) { return !$r || array_search($l, $r) === false; }
	    );

	    if (!$operators[$operator])
	        throw new Exception("Handlerbars Helper 'compare' doesn't know the operator ".$operator);

	    $result = $operators[$operator]($lvalue,$rvalue);

	    if( $result ) {
	        return $options['fn']();
	    } else {
	        return $options['inverse']();
	    }

	}

	function get($db, $index, $options) {
		
		// Comment Leo 10/06/2017: here we ask for !isset() and not just !, so that if there is an empty array, it still works...
		if (!$db || !isset($db[$index])) {
			return '';
		}
		return $db[$index];
	}

	function ifget($db, $index, $options) {

		if (!$db) {
			return '';
		}

		$context = $options['hash']['context'] ?? $options['_this'];
		$condition = false;
		// Allow to execute a method to check for the condition (eg: is user-id from the object equal to website logged in user?)
		if ($options['hash']['method']) {
			$popJSLibraryManager = PoP_ServerSide_Libraries_Factory::get_jslibrary_instance();
			$args = array(
				'domain' => $context['tls']['domain'],
				'input' => $db[$index],
			);
			$executed = $popJSLibraryManager->execute($options['hash']['method'], $args);
			foreach($executed as $value) {
				if ($value) {
					$condition = true;
					break;
				}
			}
		}
		else {
			$condition = $db[$index];
		}
		
		if ($condition) {
	        return $options['fn']();
	    } 
	    else {
	        return $options['inverse']();
	    }
	}

	function withget($db, $index, $options) {

		// Comment Leo 10/06/2017: here we ask for !isset() and not just !, so that if there is an empty array, it still works...
		if (!$db || !isset($db[$index])) {
			return '';
		}

		$context = $db[$index];

		// Expand the JS Keys
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$popManager->expandJSKeys($context);

		return $options['fn']($context);
	}

	function iffirstload($options) {

		$context = $options['hash']['context'] ?? $options['_this'];
		$pssId = $options['hash']['pssId'] ?? $context['pss']['pssId'];
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$condition = $popManager->isFirstLoad($pssId);;
		if ($condition) {
	        return $options['fn']();
	    } 
	    else {
	        return $options['inverse']();
	    }
	}

	function interceptAttr($options) {

		$context = $options['hash']['context'] ?? $options['_this'];
		$intercept = $context[GD_JS_INTERCEPT] ?? array();

		return new LS(($intercept[GD_JS_TYPE] ? ' data-intercept="'.$intercept[GD_JS_TYPE].'"' : '') . ($intercept['settings'] ? ' data-intercept-settings="'.$intercept[GD_JS_SETTINGS].'"' : '') . ($intercept[GD_JS_TARGET] ? ' target="'.$intercept[GD_JS_TARGET].'"' : '') . ($intercept[GD_JS_SKIPSTATEUPDATE] ? ' data-intercept-skipstateupdate="true"' : '') . ' style="display: none;"');
	}

	function generateId($options) {

		$context = $options['hash']['context'] ?? $options['_this'];
		$pssId = $options['hash']['pssId'] ?? $context['pss']['pssId'];
		$targetId = $options['hash']['targetId'] ?? $context['bs']['bsId'];
		$template = $options['hash']['template'] ?? $context[GD_JS_TEMPLATE];
		$fixed = $options['hash']['fixed'] ?? $context[GD_JS_FIXEDID];
		$isIdUnique = $options['hash']['idUnique'] ?? $context[GD_JS_ISIDUNIQUE];
		$group = $options['hash']['group'];
		$id = $options['fn']();
		$ignorePSRuntimeId = $context['ignorePSRuntimeId'];
		$domain = $context['tls']['domain'];
		
		// Print also the block URL. Needed to know under what URL to save the session-ids.
		// Set the URL before calling addTemplateId, where it will be needed
		$popJSRuntimeManager = PoP_ServerSide_Libraries_Factory::get_jsruntime_instance();
		$url = $options['hash']['addURL'] ? $context['tls']['feedback'][GD_URLPARAM_URL] : '';
		if ($url) {
			$popJSRuntimeManager->setBlockURL($url);
		}

		$generatedId = $popJSRuntimeManager->addTemplateId($domain, $pssId, $targetId, $template, $id, $group, $fixed, $isIdUnique, $ignorePSRuntimeId);
		$items = array();
		$items[] = 'id="'.$generatedId.'"'; 
		$items[] = 'data-templateid="'.$template.'"';
		
		// For the block, also add the URL on which it was first generated (not initialized... it can be initialized later on)
		if ($url) {
			$items[] = 'data-'.POP_PARAMS_TOPLEVEL_URL.'="'.$url.'"';
			$items[] = 'data-'.POP_PARAMS_TOPLEVEL_DOMAIN.'="'.get_domain($url).'"';
		}
		return new LS(implode(' ', $items));
	}
	function lastGeneratedId($options) {

		$context = $options['hash']['context'] ?? $options['_this'];
		$pssId = $options['hash']['pssId'] ?? $context['pss']['pssId'];
		$targetId = $options['hash']['targetId'] ?? $context['bs']['bsId'];
		$template = $options['hash']['template'] ?? $context[GD_JS_TEMPLATE];
		
		$domain = $context['tls']['domain'];
		$group = $options['hash']['group'];
		$popJSRuntimeManager = PoP_ServerSide_Libraries_Factory::get_jsruntime_instance();
		return $popJSRuntimeManager->getLastGeneratedId($domain, $pssId, $targetId, $template, $group);
	}

	function applyLightTemplate($template, $options){

		$context = $options['hash']['context'] ?? $options['_this'];
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$response = $popManager->getHtml($template, $context);
		return new LS($response/*, 'encq'*/);
	}

	// Taken from http://stackoverflow.com/questions/10232574/handlebars-js-parse-object-instead-of-object-object
	function json($context) {
	    return json_encode($context);
	}

	/* Comment Leo: taken from http://jsfiddle.net/dain/NRjUb/ */
	function enterModule($prevContext, $options){

		// The context can be passed as a param, or if null, use the current one
		$context = $options['hash']['context'] ?? $options['_this'];
		$templateName = $options['hash']['template'] ?? $context[GD_JS_TEMPLATE];

		// From the prevContext we rescue the topLevel/pageSection/block Settings
		$tls = $prevContext['tls'];
		$pss = $prevContext['pss'];
		$bs = $prevContext['bs'];
		$itemObject = $prevContext['itemObject'];
		$itemObjectDBKey = $prevContext['itemObjectDBKey'];
		$ignorePSRuntimeId = $prevContext['ignorePSRuntimeId'];
		
		// The following values, if passed as a param, then these take priority. Otherwise, use them from the previous context
		$itemDBKey = $options['hash']['itemDBKey'] ?? $prevContext['itemDBKey'];
		$items = $options['hash']['items'] ?? $prevContext['items'];

		// Add all these vars to the context for this template
		$extend = array(
			'itemObject' => $itemObject, 
			'itemObjectDBKey' => $itemObjectDBKey, 
			'itemDBKey' => $itemDBKey, 
			'items' => $items, 
			'tls' => $tls, 
			'pss' => $pss, 
			'bs' => $bs, 
			'ignorePSRuntimeId' => $ignorePSRuntimeId,
		);
		
		$domain = $tls['domain'];
		$pssId = $pss['pssId'];
		$psId = $pss['psId'];
		$bsId = $bs['bsId'];
		$bId = $bs['bId'];

		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$context = array_merge(
			$context,
			$popManager->getRuntimeConfiguration($domain, $pssId, $bsId, $templateName)
		);

		// Expand the JS Keys
		// Needed in addition to withBlock/withModule because it's not always used. Eg: controlbuttongroup.tmpl it iterates directly on modules and do enterModule on each, no #with involved
		// Do it after extending with getRuntimeConfiguration, so that these keys are also expanded
		$popManager->expandJSKeys($context);

		// ItemObjectId could be passed as an array ('dataset' is an array), so if it's the case, and it's empty, then nullify it
		$itemObjectId = $options['hash']['itemObjectId'];
		if (is_array($itemObjectId)) {
			
			if (count($itemObjectId)) {

				$itemObjectId = $itemObjectId[0];
			}
			else {

				$itemObjectId = null;
				$itemObject = null;
				$extend['itemObject'] = $itemObject;
			}
		}

		if ($options['hash']['itemDBKey'] && $itemObjectId) {

			$itemDBKey = $options['hash']['itemDBKey'];
			$itemObject = $popManager->getItemObject($domain, $itemDBKey, $itemObjectId);
			$extend['itemObject'] = $itemObject;
			$extend['itemObjectDBKey'] = $itemDBKey;
			$extend['itemDBKey'] = $itemDBKey;
			$extend['items'] = array($itemObjectId);
		}
		else if ($options['hash']['itemDBKey'] && $options['hash']['items']) {

			$extend['itemDBKey'] = $options['hash']['itemDBKey'];
			$extend['items'] = $options['hash']['items'];
		}
		else if ($options['hash']['subcomponent'] && $itemObjectId) {

			$itemDBKey = $bs['db-keys'][GD_JS_SUBCOMPONENTS][$options['hash']['subcomponent']]['db-key'];
			$itemObject = $popManager->getItemObject($domain, $itemDBKey, $itemObjectId);
			$extend['itemObject'] = $itemObject;
			$extend['itemObjectDBKey'] = $itemDBKey;
			$extend['itemDBKey'] = $itemDBKey;
			$extend['items'] = array($itemObjectId);
		}
		else if ($options['hash']['subcomponent'] && $options['hash']['items']) {

			$itemDBKey = $bs['db-keys'][GD_JS_SUBCOMPONENTS][$options['hash']['subcomponent']]['db-key'];
			$extend['itemDBKey'] = $itemDBKey;
			$extend['items'] = $options['hash']['items'];
		}
		else if ($options['hash']['items']) {

			$extend['items'] = $options['hash']['items'];
		}
		else if ($options['hash']['itemDBKey']) {

			// If only the itemDBKey has value, it means the other value passes (itemObjectId or items) is null
			// So then put everything to null
			$extend['itemDBKey'] = $options['hash']['itemDBKey'];
			$extend['itemObject'] = null;
			$extend['itemObjectDBKey'] = null;
			$extend['items'] = null;
		}

		// Make sure the items are an array
		if ($extend['items']) {
			if (!is_array($extend['items'])) {
				$extend['items'] = array($extend['items']);
			}
		}

		if ($options['hash']['feedback-msg']) {
			$extend['feedback-msg'] = $options['hash']['feedback-msg'];
		}

		// Override the Configuration with runtime values? (Eg: value inside formcomponents using DB elements)
		$overrideFields = $staticStrReplace = $runtimeStrReplace = array();
		if ($context[GD_JS_MODULEOPTIONS]) {

			$overrideFields = $context[GD_JS_MODULEOPTIONS][GD_JS_OVERRIDEFROMITEMOBJECT] ?? array();
			$staticStrReplace = $context[GD_JS_MODULEOPTIONS][GD_JS_REPLACESTRFROMITEMOBJECT] ?? array();
		}
		if ($context[GD_JS_RUNTIMEMODULEOPTIONS]) {

			$runtimeStrReplace = $context[GD_JS_RUNTIMEMODULEOPTIONS][GD_JS_REPLACESTRFROMITEMOBJECT] ?? array();
		}
		if ($overrideFields) {

			$popManager->overrideFromItemObject($itemObject, $extend, $overrideFields);
		}
		$strReplace = array_merge(
			$staticStrReplace,
			$runtimeStrReplace
		);
		if ($strReplace) {

			$popManager->replaceFromItemObject($domain, $pssId, $bsId, $templateName, $itemObject, $extend, $strReplace);
		}

		// Needed for the BlockGroups
		$parentContext = $options['hash']['parentContext'] ?? array();
		if ($parentContext) {
			$extend['parent-context'] = $parentContext;

			$rootContext = $parentContext;
			while ($rootContext['parent-context']) {
				$rootContext = $rootContext['parent-context'];
			}
			$extend['root-context'] = $rootContext;
		}
		
		$context = array_merge(
			$context,
			$extend
		);

		$response = $popManager->getHtml($templateName, $context);

		// Add the CSS style links in the body?
		if (PoP_Frontend_ServerUtils::include_resources_in_body()) {
			
			// // Include only those resources that go in the body
			// global $pop_resourceloaderprocessor_manager;
			// if ($resources = $pop_resourceloaderprocessor_manager->filter_in_body($resources)) {
			if ($resources = $context[GD_JS_RESOURCES]) {

				$popResourceLoader = PoP_ServerSide_Libraries_Factory::get_resourceloader_instance();
				$resourceTags = $popResourceLoader->includeResources($domain, $bId, $resources, true);
				$response = $resourceTags.$response;
			}
			// }
		}

		if ($options['hash']['unsafe']) {
			return $response;
		}
		
		return new LS($response/*, 'encq'*/);
	}

	function getMultiLayoutKey($itemDBKey, $itemObject) {

		// Define in the jquery_constants what fields form the key, getting the field values from the itemObject,
		// to identify the layout
		$multilayout_keyfields = PoP_Frontend_ConfigurationUtils::get_multilayout_keyfields();
		$keyFields = $multilayout_keyfields[$itemDBKey] ?? array();
		
		if (!$keyFields) {
			return 'default';
		}

		$keyParts = array();
		foreach($keyFields as $field) {

			$keyParts[] = $itemObject[$field];
		}
		return implode('-', $keyParts);
	}

	function withLayout($itemDBKey, $itemObjectId, $multipleLayouts, $context, $options) {

		$tls = $context['tls'];
		$domain = $tls['domain'];

		// Obtain the key composed as: 'post_type'-'cat'
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$itemObject = $popManager->getItemObject($domain, $itemDBKey, $itemObjectId);
		
		// keys 'post-type' and 'cat' must always be there!
		$key = $this->getMultiLayoutKey($itemDBKey, $itemObject);
		
		// Fetch the layout for that particular configuration
		$layout = $multipleLayouts[$key];
		if (!$layout) {
			$layout = $multipleLayouts['default'];
		}

		// If still no layout, then do nothing
		if (!$layout) {
			return '';
		}

		// Render the content from this layout
		$layoutContext = $context[GD_JS_MODULES][$layout];

		// Add itemDBKey and itemObjectId back into the context
		$layoutContext = array_merge(
			$layoutContext,
			array(
				'itemDBKey' => $itemDBKey, 
				'itemObjectId' => $itemObjectId,
			)
		);

		// Expand the JS Keys
		$popManager->expandJSKeys($layoutContext);

		return $options['fn']($layoutContext);
	}

	function layoutLabel($itemDBKey, $itemObject, $options) {

		// Obtain the key composed as: 'post_type'-'cat'
		// keys 'post-type' and 'cat' must always be there!

		$key = $this->getMultiLayoutKey($itemDBKey, $itemObject);
		
		$multilayout_labels = PoP_Frontend_ConfigurationUtils::get_multilayout_labels();
		return $multilayout_labels[$key] ?? '';
	}

	// function withOptions($context, $options) {

	// 	if ($context) {

	// 		// Read all hash options, and add them to the Context
	// 		$context = array_merge(
	// 			$context,
	// 			$options['hash']
	// 		);

	// 		// Expand the JS Keys
	// 		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
	// 		$popManager->expandJSKeys($context);

	// 		return $options['fn']($context);
	// 	}
	// }

	function withBlock($context, $blockSettingsId, $options) { 

		// Comment Leo 10/06/2017: here we ask for !isset() and not just !, so that if there is an empty array, it still works...
		if (!$context || !isset($context[GD_JS_MODULES]) || !isset($context[GD_JS_MODULES][$blockSettingsId])) {

			return;
		}

		// Go down to the module
		$context = $context[GD_JS_MODULES][$blockSettingsId];

		// Expand the JS Keys
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$popManager->expandJSKeys($context);

		return $options['fn']($context);
	}

	function withModule($context, $module, $options) { 

		// Comment Leo 10/06/2017: here we ask for !isset() and not just !, so that if there is an empty array, it still works...
		if (!$context || !isset($context[GD_JS_SETTINGSIDS]) || !isset($context[GD_JS_SETTINGSIDS][$module])) {

			return;
		}

		// Get the module settings id from the configuration
		$moduleSettingsId = $context[GD_JS_SETTINGSIDS][$module];

		// Comment Leo 10/06/2017: here we ask for !isset() and not just !, so that if there is an empty array, it still works...
		if (!isset($context[GD_JS_MODULES]) || !isset($context[GD_JS_MODULES][$moduleSettingsId])) {

			return;
		}

		// Go down to the module
		$context = $context[GD_JS_MODULES][$moduleSettingsId];

		// Expand the JS Keys
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$popManager->expandJSKeys($context);

		// Read all hash options, and add them to the Context
		$context = array_merge(
			$context,
			$options['hash'] ?? array()
		);

		return $options['fn']($context);
	}

	function withSublevel($sublevel, $options) {

		$context = $options['hash']['context'] ?? $options['_this'];

		// Expand the JS Keys
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$popManager->expandJSKeys($context);

		return $options['fn']($context[$sublevel]);
	}

	function withItemObject($itemDBKey, $itemObjectId, $options) {

		$context = $options['hash']['context'] ?? $options['_this'];
		$tls = $context['tls'];
		$domain = $tls['domain'];

		// Replace the context with only the itemObject
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$context = $popManager->getItemObject($domain, $itemDBKey, $itemObjectId);
		
		return $options['fn']($context);
	}


	// Taken from: https://github.com/raDiesle/Handlebars.js-helpers-collection#JOIN
	//function join($items, $block) { return "function join(items, block)"; /*
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

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_serverside_helpers;
$pop_serverside_helpers = new PoP_ServerSide_Helpers();