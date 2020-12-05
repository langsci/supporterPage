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

{include file="frontend/components/header.tpl" pageTitleTranslated="$title"}

<h2>{translate key="plugins.generic.title.supporterPage"}</h2>
<p>{eval var=$intro}</p>

<ol>
	{foreach from=$rankedSupporters item=user}
	    <li>{if $user.url}<a href="{$user.url|strip_unsafe_html}">{/if}{$user.givenName|strip_unsafe_html} {$user.familyName|strip_unsafe_html}{if $user.url}</a>{/if}{if $user.salutation}, {$user.salutation|strip_unsafe_html}{/if}
			{if $user.affiliation}({$user.affiliation|strip_unsafe_html}){/if}
		</li>
	{/foreach}
</ol>

{include file="frontend/components/footer.tpl"}

