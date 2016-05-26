<?php

/**
 * @file plugins/generic/supporterPage/SupporterPagePlugin.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SupporterPagePlugin
 *
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class SupporterPagePlugin extends GenericPlugin {


	function register($category, $path) {
			
		if (parent::register($category, $path)) {
			$this->addLocaleData();
			
			if ($this->getEnabled()) {
				HookRegistry::register ('LoadHandler', array(&$this, 'handleSupporterPageRequest'));
			}
			return true;
		}
		return false;

	}

	function handleSupporterPageRequest($hookName, $args) {

		$request = $this->getRequest();
		$templateMgr = TemplateManager::getManager($request);

		$page = $args[0];
		$op = $args[1];
		if ($page == 'community' && in_array($op, array('supporters'))) {

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

	function getTemplatePath() {
		return parent::getTemplatePath() . 'templates/';
	}

	function getInstallSchemaFile() {
		return $this->getPluginPath() . '/schema.xml';
	}

}

?>
