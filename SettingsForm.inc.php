<?php

/**
 * @file plugins/generic/supporterPage/SettingsForm.inc.php
 *
 * Copyright (c) 2020 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 * @class SettingsForm
 */

import('lib.pkp.classes.form.Form');
import('plugins.generic.supporterPage.SupporterPageDAO');

class SettingsForm extends Form {

	/** @var int Associated context ID */
	private $_contextId;

	/** @var SupporterPagePlugin object */
	private $_plugin;

	/**
	 * Constructor
	 * @param $plugin SupporterPagePlugin object
	 * @param $contextId int Context ID
	 */
	function __construct($plugin, $contextId) {
		$this->_contextId = $contextId;
		$this->_plugin = $plugin;
		
		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$contextId = $this->_contextId;
		$plugin = $this->_plugin;

		$supporterPageDAO = new SupporterPageDAO;
		$prominentUsers = $supporterPageDAO->getProminentSupportersUsernames();

		$this->setData('prominentUsers', $prominentUsers);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('add', 'remove'));
	}

	/**
	 * Fetch the form.
	 * @copydoc Form::fetch()
	 */
	function fetch($request, $template = NULL, $display = false) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		
		return parent::fetch($request);
	}

	/**
	 * Save settings. 
	 */
	function execute(...$functionArgs) {
		$plugin = $this->_plugin;
		$contextId = $this->_contextId;
		$supporterPageDAO = new SupporterPageDAO;
		$supporterPageDAO->addProminentUser(trim($this->getData('add')), $this->_plugin->getCurrentContextId());
		$supporterPageDAO->removeProminentUser($this->getData('remove'), $this->_plugin->getCurrentContextId());
	}
}

?>
