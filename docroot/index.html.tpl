{include file="header.tpl" title="Hello, world"}

<h1>Hello, world</h1>

<p>This is index.html.tpl.  Find me in
{$smarty.const.TF_DOCUMENT_ROOT}index.html.tpl</p>

<h2>Naming convention</h2>

<p>http://localhost/test/www/somewhere/index.html -&gt;
{$smarty.const.TF_DOCUMENT_ROOT}<b>somewhere/index.html<i>.tpl</i></b></p>

<p>The last .tpl is very important.  We recommend to disallow direct
access to .tpl files usnig .htaccess</p>

{include file="footer.tpl"}
