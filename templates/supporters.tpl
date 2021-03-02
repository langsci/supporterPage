{**
 * plugins/generic/supporterPage/templates/supporters.tpl
 *
 * Copyright (c) 2020 Language Science Press
 * Developed by Ronald Steffen
 * Distributed under the MIT license. For full terms see the file docs/License.
 *
 *
 *}

<link rel="stylesheet" href="{$baseUrl}/plugins/generic/supporterPage/css/supporterPage.css">


	{include file="frontend/components/header.tpl"}


<div class="supporterPage">

	<h2>{translate key="plugins.generic.title.supporterPage"}</h2>
	<p>{eval var=$intro}</p>

	<ol>
		{$htmlList}
	</ol>
</div>

{include file="frontend/components/footer.tpl"}