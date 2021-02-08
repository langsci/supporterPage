- Supporter Page Plugin
- Version: 2.0.1.0
- Author: Carola Fanselow, Ronald Steffen

About
-----
This plugin lists all Language Science Press Supporters with salutation, first name, last name, url and affiliation.

License
-------
Copyright (c) 2020 Language Science Press
This plugin is licensed under the GNU General Public License v2. 

System Requirements
-------------------
This plugin is compatible with...
 - OMP 3.2.1

Installation
------------
To install the plugin:
 - Upload the tar.gz file in OMP (Management > Website Settings > Plugins > Generic Plugins)
 - enbale SupporterPage Plugin frontend view by creating a Remote-URL menu entry pointing at `http://<your monograph domain name and main path>/supporters`
 
Update
------------
To update the database from privious plugin versions execute the following SQL statement:

`insert into user_settings (user_id, setting_name, setting_value, setting_type) select user_id,'prominentSupporter','1','string' from langsci_prominent_supporters;`

Support
---------------
A documentation on this plugin can be found in pluginDocumentation_supporterPage.md


