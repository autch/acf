{include file="header.tpl" title="Tiny RSS reader"}

<h1>RSS</h1>

<p>This is rss.html.tpl.  Find me in
{$smarty.const.TF_DOCUMENT_ROOT}rss.html.tpl</p>

<h2>MSN 産経ニュース - 注目</h2>

{load_rss url="http://sankei.jp.msn.com/rss/news/points.xml" assign="rss"}
<div>
{foreach from=$rss item=item}
<a href="{$item.link|escape}">{$item.title|strip|escape}</a><br>
{/foreach}
</div>

{include file="footer.tpl"}
