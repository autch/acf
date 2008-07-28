{include file="header.tpl" title="Poor link page"}

<h1>Link</h1>

<p>This is link.html.tpl.  Find me in
{$smarty.const.TF_DOCUMENT_ROOT}link.html.tpl</p>

{load_table src="`$smarty.const.TF_LIB_PATH`/etc/links"
            cols="title,link,banner,width,height" assign="links"}

{foreach from=$links item=category}
<div><b>{$category.category|escape}</b><br>
{foreach name=items from=$category.items item=item}
&nbsp; <a href="{$item.link|escape}">{$item.title|escape}</a><br>
{/foreach}
</div>
{/foreach}

{include file="footer.tpl"}
