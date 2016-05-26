<?php

/**
 * @file plugins/generic/supporterPage/SettingsForm.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SettingsForm
 */

import('lib.pkp.classes.form.Form');
import('plugins.generic.supporterPage.SupporterPageDAO');

class SettingsForm extends Form {

	/** @var int Associated context ID */
	private $_contextId;

	/** @var WebFeedPlugin Web feed plugin */
	private $_plugin;

	/**
	 * Constructor
	 * @param $plugin WebFeedPlugin Web feed plugin
	 * @param $contextId int Context ID
	 */
	function SettingsForm($plugin, $contextId) {
		$this->_contextId = $contextId;
		$this->_plugin = $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');
		$this->addCheck(new FormValidatorPost($this));
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
	function fetch($request) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		return parent::fetch($request);
	}

	/**
	 * Save settings. 
	 */
	function execute() {
		$plugin = $this->_plugin;
		$contextId = $this->_contextId;
		$supporterPageDAO = new SupporterPageDAO;
		$supporterPageDAO->addProminentUser(trim($this->getData('add')));
		$supporterPageDAO->removeProminentUser($this->getData('remove'));
	}
}

?>
