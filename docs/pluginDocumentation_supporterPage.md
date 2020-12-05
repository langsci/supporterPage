Key data
============

- name of the plugin: Supporter Page Plugin
- author: Carola Fanselow, Ronald Steffen
- current version: 2.0.1.0
- tested on OMP version: 3.2.1
- github link: https://github.com/langsci/supporterPage.git
- community plugin: no
- date: 2020/12/05

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
- new/additional templates: 2

		supporter.tpl
		settingsForm.tpl

Database access, server access
-----------------------------
- reading access to OMP tables: 4

		users
		user_settings
		user_user_groups
		user_group_settings

- writing access to OMP tables: 1

		user_settings

- nonrecurring server access: no

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
- number of files: 12

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




