{**
 * @file plugins/generic/supporterPage/templates/settingsForm.tpl
 *
 * Copyright (c) 2020 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 *}


<link rel="stylesheet" href="{$baseUrl}/plugins/generic/supporterPage/css/supporterPage.css">

<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#supporterPageSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

<div id="supporterPageSettings">

<h3>{translate key="plugins.generic.supporterPage.settings"}</h3>

<p>{translate key="plugins.generic.supporterPage.settings.intro"}</p>

<div id="prominentUsers">
	{foreach from=$prominentUsers item=username}
		{$username}, 
	{/foreach}
</div>

<form class="pkp_form" id="supporterPageSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}">
	{csrf}
	{include file="common/formErrors.tpl"}
	{fbvFormArea id="seriesOverviewSettingsFormArea"}
		{fbvFormSection list=true}
			<span>{translate key="plugins.generic.supporterPage.settings.addIntro"}</span>
			{fbvElement type="text" id="add" value=$add size=$fbvStyles.size.SMALL}
		{/fbvFormSection}

		{fbvFormSection list=true}
			<span>{translate key="plugins.generic.supporterPage.settings.removeIntro"}</span>
			{fbvElement type="text" id="remove" value=$remove size=$fbvStyles.size.SMALL}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormButtons submitText="common.save"}
</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
