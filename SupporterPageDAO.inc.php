<?php

/**
 * @file plugins/generic/supporterPage/SupporterPageDAO.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SupporterPageDAO
 *
 * Class for Supporter Page DAO.
 */

class SupporterPageDAO extends DAO {

	function SupporterPageDAO() {
		parent::DAO();
	}

	function getUserGroupIdByName($user_group_name) {
		$result = $this->retrieve(
			"SELECT user_group_id FROM user_group_settings WHERE
				locale='en_US' AND
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

	function getSupporters($supporterGroupId,$locale) {

		$result = $this->retrieve(
			"SELECT a.user_id, a.salutation, a.first_name, a.last_name, a.url, c.setting_value AS affiliation, b.user_group_id FROM users a LEFT JOIN user_user_groups b LEFT JOIN user_settings c ON b.user_id=c.user_id on b.user_id=a.user_id WHERE b.user_group_id=".$supporterGroupId." AND c.locale='".$locale."' AND c.setting_name='affiliation'"
		);

		if ($result->RecordCount() == 0) {
			$result->Close();
			return array();
		} else {
			$supporters = array();
			$i = 0;
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);
				$supporters[$i]['id'] = $this->convertFromDB($row['user_id'],null);
				$supporters[$i]['salutation'] = $this->convertFromDB($row['salutation'],null);
				$supporters[$i]['firstName'] = $this->convertFromDB($row['first_name'],null);
				$supporters[$i]['lastName'] = $this->convertFromDB($row['last_name'],null);
				$supporters[$i]['url'] = $this->convertFromDB($row['url'],null);
				$supporters[$i]['affiliation'] = $this->convertFromDB($row['affiliation'],null);
				$i++;		 
				$result->MoveNext();
			}
			$result->Close();
			return $supporters;
		}
	}

	function getProminentSupporters() {

		$result = $this->retrieve(
			"SELECT user_id FROM langsci_prominent_supporters");

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

	function getProminentSupportersUsernames() {

		$result = $this->retrieve(
			"SELECT b.username FROM langsci_prominent_supporters a LEFT JOIN users b ON a.user_id=b.user_id ORDER BY b.username");

		if ($result->RecordCount() == 0) {
			$result->Close();
			return array();
		} else {
			$prominentUsers = array();
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);
				$prominentUsers[] = $this->convertFromDB($row['username'],null);	 
				$result->MoveNext();
			}
			$result->Close();
			return $prominentUsers;
		}
	}

	function addProminentUser($username) {

		$result = $this->retrieve("SELECT user_id FROM users WHERE username='".$username."'");

		if ($result->RecordCount() == 0) {
			$result->Close();	 
			return false;
		} else {
			$row = $result->getRowAssoc(false);
			$userId = $this->convertFromDB($row['user_id'],null);
			$result->Close();
 			$this->update('INSERT INTO langsci_prominent_supporters (user_id) VALUES('.$userId.')');
			return true;
		}
	}

	function removeProminentUser($username) {

		$result = $this->retrieve("SELECT user_id FROM users WHERE username='".$username."'");

		if ($result->RecordCount() == 0) {
			$result->Close();	 
			return false;
		} else {
			$row = $result->getRowAssoc(false);
			$userId = $this->convertFromDB($row['user_id'],null);
			$result->Close();
 			$this->update('DELETE FROM langsci_prominent_supporters WHERE user_id='.$userId);
			return true;
		}
	}
}

?>
