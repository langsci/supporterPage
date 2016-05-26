Key data
============

- name of the plugin: Supporter Page Plugin
- author: Carola Fanselow
- current version: 1.0
- tested on OMP version: 1.2.0
- github link: https://github.com/langsci/supporterPage.git
- community plugin: no
- date: 2016/05/25

Description
============

This plugin lists all Language Science Press Supporters with salutation, first name, last name, url and affiliation. Details can be set in the settings of the plugin. 
 
Implementation
================

Hooks
-----
- used hooks: 1

		LoadHandler

New pages
------
- new pages: 1

		[press]/community/supporters

Templates
---------
- templates that replace other templates: 0
- templates that are modified with template hooks: 0
- new/additional templates: 1

		supporter.tpl
		settingsForm.tpl

Database access, server access
-----------------------------
- reading access to OMP tables: 4

		users
		user_settings
		user_user_groups
		user_group_settings

- writing access to OMP tables: 0
- new tables: 1

		langsci_prominent_users

- nonrecurring server access: yes

		creating table langsci_prominent_users during installation

- recurring server access: no
 
Classes, plugins, external software
-----------------------
- OMP classes used (php): 8
	
		GenericPlugin
		Handler
		DAO
		AjaxModal
		Form
		LinkAction
		JSONMessage
		TemplateManager

- OMP classes used (js, jqeury, ajax): 1

		AjaxFormHandler

- necessary plugins: 0
- optional plugins: 0
- use of external software: no
- file upload: no
 
Metrics
--------
- number of files: 13
- lines of code: 756

Settings
--------
- settings: 2

		add prominent tag
		remove prominent tag

Plugin category
----------
- plugin category: generic

Other
=============
- does using the plugin require special (background)-knowledge?: no
- access restrictions: no
- adds css: yes




