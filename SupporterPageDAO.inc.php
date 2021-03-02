<?php

/**
 * @file plugins/generic/supporterPage/SupporterPageDAO.inc.php
 *
 * Copyright (c) 2020 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 * @class SupporterPageDAO
 *
 * Class for Supporter Page DAO.
 */

class SupporterPageDAO extends DAO {

    function __construct() {
		parent::__construct();
	}

	function getUserGroupIdByName($user_group_name, $locale) {
		$result = $this->retrieve(
			"SELECT user_group_id FROM user_group_settings WHERE
				locale='".$locale."' AND
				setting_name = 'name' AND
				setting_value = '" . $user_group_name . "'"
		);

		if ($result->RecordCount() == 0) {
			$result->Close();
			return null;
		} else {
			$row = $result->getRowAssoc(false);
			$result->Close();
			return $this->convertFromDB($row['user_group_id'],null);
		}	
	}

	function getSupporters($locale) {
	   
	    $supporterGroupId = $this->getUserGroupIdByName('Supporter',$locale);
	    
		// enable or disable profiling
		$profiling = false;
		if ($profiling) {
			$startFunction = Core::microtime();
		}
	    
		$result = $this->retrieve(
			"SELECT a.user_id FROM users a LEFT JOIN user_user_groups b ON b.user_id=a.user_id WHERE 
                b.user_group_id=".$supporterGroupId
		    );

		if ($result->RecordCount() == 0) {
			$result->Close();
			return array();
		} else {
			$supporters = array();
			$i = 0;
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);

				$userDao = DAORegistry::getDAO('UserDAO');
				$user = $userDao->getById($this->convertFromDB($row['user_id'],null));
				
				$supporters[$i]['id'] = $this->convertFromDB($row['user_id'],null);
				$supporters[$i]['salutation'] = $user->getPreferredPublicName($locale);
				$supporters[$i]['givenName'] = $user->getGivenName($locale);
				$supporters[$i]['familyName'] = $user->getFamilyName($locale);
				$supporters[$i]['url'] = $user->getUrl();
				$supporters[$i]['affiliation'] = $user->getAffiliation($locale);
				$i++;		 
				$result->MoveNext();
			}
			$result->Close();
			
			if ($profiling) {
				error_log("RS_DEBUG:".basename(__FILE__).":".__FUNCTION__.":SQL execution time: ".print_r(Core::microtime()-$start,true));
			}
			return $supporters;
		}
	}

	function getProminentSupporters($locale) {
	    
	    $supporterGroupId = $this->getUserGroupIdByName('Supporter',$locale);

	    $result = $this->retrieve(
	        "SELECT a.user_id FROM users a LEFT JOIN user_user_groups b ON b.user_id=a.user_id JOIN user_settings c ON a.user_id=c.user_id WHERE b.user_group_id=".$supporterGroupId." and c.setting_name='prominentSupporter'  and c.setting_value='1';"
	        );

		if ($result->RecordCount() == 0) {
			$result->Close();
			return array();
		} else {
			$prominentUsers = array();
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);
				$prominentUsers[] = $this->convertFromDB($row['user_id'],null);	 
				$result->MoveNext();
			}
			$result->Close();
			return $prominentUsers;
		}
	}

	function getProminentSupportersUsernames($locale) {
	    $userDao = DAORegistry::getDAO('UserDAO');
	    $prominentUsers = [];
	    foreach ($this->getProminentSupporters($locale) as $userID) {
	        $prominentUsers[] = $userDao->getById($userID)->getUserName();
	    }
	    
		return $prominentUsers;
	}
	
	function addProminentUser($username, $context) {	 
	    $userDao = DAORegistry::getDAO('UserDAO');
	    $user = $userDao->getByUsername($username, false);
	    $userSettingsDao = DAORegistry::getDAO('UserSettingsDAO');
	    if ($user != NULL) {
	        $userSettingsDao->updateByAssoc($user->getID(), 'prominentSupporter', '1', 'string', null, null);
	    }
	}

	function removeProminentUser($username, $context) {
	    $userDao = DAORegistry::getDAO('UserDAO');
	    $user = $userDao->getByUsername($username, false);
	    $userSettingsDao = DAORegistry::getDAO('UserSettingsDAO');
	    if ($user != NULL) {
	        $userSettingsDao->deleteByAssoc($user->getID(), 'prominentSupporter', null, null);
	    }
	}
}

?>
