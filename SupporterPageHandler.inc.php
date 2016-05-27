<?php

/**
 * @file plugins/generic/supporterPage/SupporterPageHandler.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
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

		$locale = AppLocale::getLocale();
		$supporterPageDAO = new SupporterPageDAO;
		$supporterGroupId = $supporterPageDAO->getUserGroupIdByName('Supporter',$request->getContext()->getId());
		if ($supporterGroupId) {
			$supporters = $supporterPageDAO->getSupporters($supporterGroupId,$locale);
			$prominentUsers = $supporterPageDAO->getProminentSupporters();
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

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pageTitle', 'plugins.generic.title.supporterPage');
		$templateMgr->assign('prominentUsers', $prominentUsers);
		$templateMgr->assign('baseUrl',$request->getBaseUrl());
		$templateMgr->assign('rankedSupporters', $rankedSupporters);
		$templateMgr->assign('intro', __('plugins.generic.supporterPage.intro'));

		$supporterPagePlugin = PluginRegistry::getPlugin('generic', SUPPORTERPAGE_PLUGIN_NAME);
		$templateMgr->display($supporterPagePlugin->getTemplatePath().'supporters.tpl');

	}

}

?>
