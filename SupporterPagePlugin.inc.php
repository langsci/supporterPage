<?php

/**
 * @file plugins/generic/supporterPage/SupporterPagePlugin.inc.php
 *
 * Copyright (c) 2020 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 * @class SupporterPagePlugin
 *
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class SupporterPagePlugin extends GenericPlugin {


    function register($category, $path, $mainContextId = NULL) {
			
        if (parent::register($category, $path, $mainContextId)) {
            if ($this->getEnabled($mainContextId)) {
    			$this->addLocaleData();
    			
    			if ($this->getEnabled()) {
    				HookRegistry::register ('LoadHandler', array(&$this, 'handleSupporterPageRequest'));
    			}
            }
			return true;
		}
		return false;
	}

	function handleSupporterPageRequest($hookName, $args) {

		$request = $this->getRequest();
		$templateMgr = TemplateManager::getManager($request);

		$page = $args[0];
		if ($page == 'supporters') {
			$args[1] =$page;
			define('SUPPORTERPAGE_PLUGIN_NAME', $this->getName());
			define('HANDLER_CLASS', 'SupporterPageHandler');
			$this->import('SupporterPageHandler');
			return true;
		}		
		return false;
	}
	
	/**
	 * @see Plugin::getActions()
	 */
	function getActions($request, $verb) {
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		return array_merge(
			$this->getEnabled()?array(
				new LinkAction(
					'settings',
					new AjaxModal(
						$router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
						$this->getDisplayName()
					),
					__('manager.plugins.settings'),
					null
				),
			):array(),
			parent::getActions($request, $verb)
		);
	}

 	/**
	 * @see Plugin::manage()
	 */
	function manage($args, $request) {
		switch ($request->getUserVar('verb')) {
			case 'settings':
				$context = $request->getContext();
				
				AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON,  LOCALE_COMPONENT_PKP_MANAGER);
				$templateMgr = TemplateManager::getManager($request);
				$templateMgr->registerPlugin('function', 'plugin_url', array($this, 'smartyPluginUrl'));
				
				$this->import('SettingsForm');
				$form = new SettingsForm($this, $context->getId());

				if ($request->getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						return new JSONMessage(true);
					}
				} else {
					$form->initData();
				}
				return new JSONMessage(true, $form->fetch($request));
		}
		return parent::manage($args, $request);
	}


	function getDisplayName() {
		return __('plugins.generic.supporterPage.displayName');
	}

	function getDescription() {
		return __('plugins.generic.supporterPage.description');
	}

	/**
	 * Get the name of the settings file to be installed on new context
	 * creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
	    return $this->getPluginPath() . '/settings.xml';
	}
}

?>
