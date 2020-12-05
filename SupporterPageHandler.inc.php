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
	    
	    $supporterPagePlugin = PluginRegistry::getPlugin('generic', SUPPORTERPAGE_PLUGIN_NAME);
	    
	    $templateMgr = TemplateManager::getManager($request);
	    $this->setupTemplate($request); // important for getting the correct menue
	    $templateMgr->setCaching(Smarty::CACHING_LIFETIME_SAVED);
	    $templateMgr->clearTemplateCache();
	    $templateMgr->setCompileCheck(false);
	    $templateMgr->setCacheLifetime(24*60*60);
	    //TODO RS figure out why isCached() is not working -> there are hints this is a bug solved by upgrading Smarty
	    if (!$templateMgr->isCached('../../../plugins/generic/supporterPage/templates/supporters.tpl')) {
	        error_log("RS_DEBUG:".basename(__FILE__).":".__FUNCTION__.":??? ".print_r("IS NOT CACHED !!!",true));
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
    		} else {
    			$rankedSupporters=array();
    		}
    		$templateMgr->assign('rankedSupporters', $rankedSupporters);
	    }

		$templateMgr->assign('pageTitle', 'plugins.generic.title.supporterPage');
		$templateMgr->assign('baseUrl',$request->getBaseUrl());
		$templateMgr->assign('intro', __('plugins.generic.supporterPage.intro'));
		
        $start = Core::microtime();        
		$templateMgr->display($supporterPagePlugin->getTemplateResource('supporters.tpl'));
		error_log("RS_DEBUG:".basename(__FILE__).":".__FUNCTION__.":Smarty execution time: ".print_r(Core::microtime()-$start,true));
	}

}

?>
