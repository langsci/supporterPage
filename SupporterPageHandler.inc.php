<?php

/**
 * @file plugins/generic/supporterPage/SupporterPageHandler.inc.php
 *
 * Copyright (c) 2020 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 * @class SupporterPageHandler
 *
 * Find the content and display the appropriate page
 *
 */

import('classes.handler.Handler');
import('plugins.generic.supporterPage.SupporterPageDAO');

class SupporterPageHandler extends Handler {	

	function supporters($args, $request) {

		// enable or disable profiling
		$profiling = false;
		if ($profiling) {
			$startFunction = Core::microtime();
		}

	    $supporterPagePlugin = PluginRegistry::getPlugin('generic', SUPPORTERPAGE_PLUGIN_NAME);
		$this->setupTemplate($request); // important for getting the correct menue

		$templateMgr = TemplateManager::getManager($request);
		
    		$locale = AppLocale::getLocale();
			$supporterPageDAO = new SupporterPageDAO;
    		$supporterGroupId = $supporterPageDAO->getUserGroupIdByName('Supporter',$locale);
    		$prominentUsers = array();
    		if ($supporterGroupId) {
    			$supporters = $supporterPageDAO->getSupporters($locale);
    			$prominentUsers = $supporterPageDAO->getProminentSupporters($locale);
    			$section1 = array();
    			$section2 = array();
    			for ($i=0; $i<sizeof($supporters); $i++) {
    				if (in_array($supporters[$i]['id'],$prominentUsers)) {
    					$section1[] = $supporters[$i];
    				} else {
    					$section2[] = $supporters[$i];
    				}
    			} 
    			$rankedSupporters = array_merge($section1,$section2);
				$htmlList = "";
				foreach ($rankedSupporters as $user) {
					$htmlList = $htmlList . "<li>";
					if ($user['url']) {
						$htmlList = $htmlList . "<a href=\"" . strip_tags($user['url']) . "\">";
					}
					$htmlList = $htmlList . strip_tags($user['givenName']) . " " . strip_tags($user['familyName']);
					if ($user['url']) {
						$htmlList = $htmlList . "</a>";
					}
					if ($user['salutation']) $htmlList = $htmlList . ", " . strip_tags($user['salutation']);
					if ($user['affiliation']) $htmlList = $htmlList . ", " . strip_tags($user['affiliation']);
					$htmlList = $htmlList . "</li>";
				}
    		} else {
    			$rankedSupporters=array();
    		}
    		$templateMgr->assign('htmlList', $htmlList);

		$templateMgr->assign('pageTitle', 'plugins.generic.title.supporterPage');
		$templateMgr->assign('baseUrl',$request->getBaseUrl());
		$templateMgr->assign('intro', __('plugins.generic.supporterPage.intro')); 
		
		if ($profiling) {
        	$start = Core::microtime();       
		} 
		$templateMgr->display($supporterPagePlugin->getTemplateResource('supporters.tpl'));
		if ($profiling) {
			error_log("RS_DEBUG:".basename(__FILE__).":".__FUNCTION__.":Smarty execution time: ".print_r(Core::microtime()-$start,true));
			error_log("RS_DEBUG:".basename(__FILE__).":".__FUNCTION__.":Function execution time: ".print_r(Core::microtime()-$startFunction,true));
		}
	}

}

?>
