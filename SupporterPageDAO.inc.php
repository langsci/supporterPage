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
			return $this->convertFromDB($row['user_group_id']);
		}	
	}

	function getSupporters($supporterGroupId) {

		$result = $this->retrieve(
			"select a.user_id, a.salutation, a.first_name, a.last_name, a.url, c.setting_value as affiliation, b.user_group_id from users a left join user_user_groups b left join user_settings c on b.user_id=c.user_id on b.user_id=a.user_id where b.user_group_id=".$supporterGroupId." and c.locale='en_US' and c.setting_name='affiliation'"
		);

		if ($result->RecordCount() == 0) {
			$result->Close();
			return array();
		} else {
			$supporters = array();
			$i = 0;
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);
				$supporters[$i]['id'] = $this->convertFromDB($row['user_id']);
				$supporters[$i]['salutation'] = $this->convertFromDB($row['salutation']);
				$supporters[$i]['firstName'] = $this->convertFromDB($row['first_name']);
				$supporters[$i]['lastName'] = $this->convertFromDB($row['last_name']);
				$supporters[$i]['url'] = $this->convertFromDB($row['url']);
				$supporters[$i]['affiliation'] = $this->convertFromDB($row['affiliation']);
				$i++;		 
				$result->MoveNext();
			}
			$result->Close();
			return $supporters;
		}
	}

	function getProminentSupporters() {

		$result = $this->retrieve(
			"select user_id from langsci_prominent_supporters");

		if ($result->RecordCount() == 0) {
			$result->Close();
			return array();
		} else {
			$prominentUsers = array();
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);
				$prominentUsers[] = $this->convertFromDB($row['user_id']);	 
				$result->MoveNext();
			}
			$result->Close();
			return $prominentUsers;
		}
	}

	function getProminentSupportersUsernames() {

		$result = $this->retrieve(
			"select b.username from langsci_prominent_supporters a left join users b on a.user_id=b.user_id order by b.username");

		if ($result->RecordCount() == 0) {
			$result->Close();
			return array();
		} else {
			$prominentUsers = array();
			while (!$result->EOF) {
				$row = $result->getRowAssoc(false);
				$prominentUsers[] = $this->convertFromDB($row['username']);	 
				$result->MoveNext();
			}
			$result->Close();
			return $prominentUsers;
		}
	}

	function addProminentUser($username) {

		$result = $this->retrieve("select user_id from users where username='".$username."'");

		if ($result->RecordCount() == 0) {
			$result->Close();	 
			return false;
		} else {
			$row = $result->getRowAssoc(false);
			$userId = $this->convertFromDB($row['user_id']);
			$result->Close();
 			$this->update('insert into langsci_prominent_supporters (user_id) values('.$userId.')');
			return true;
		}
	}

	function removeProminentUser($username) {

		$result = $this->retrieve("select user_id from users where username='".$username."'");

		if ($result->RecordCount() == 0) {
			$result->Close();	 
			return false;
		} else {
			$row = $result->getRowAssoc(false);
			$userId = $this->convertFromDB($row['user_id']);
			$result->Close();
 			$this->update('delete from langsci_prominent_supporters where user_id='.$userId);
			return true;
		}
	}
}

?>
