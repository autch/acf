<table class="outer" border="1">
<tr>
  <th><a href="?o={if $order == 'name'}!{/if}name" rel="nofollow">�ե�����̾</a></th>
  <th><a href="?o={if $order == 'date'}!{/if}date" rel="nofollow">�ǽ���������</a></th>
  <th><a href="?o={if $order == 'size'}!{/if}size" rel="nofollow">�ե����륵����</a></th>
</tr>
{foreach from=$files item=file}
<tr class="{cycle name="eo" values="even,odd"}">
  <td class="filename"><a href="{$file.linkto|escape}">{$file.filename|escape}</a></td>
  <td class="lastmodified">{$file.date|date_format:"%Y-%m-%d %H:%M:%S"|escape}</td>
  <td class="size">{$file.size|number_format|escape}</td>
</tr>
{/foreach}
<tr>
<th>{$count|number_format|escape} �ĤΥե�����</th>
<th></th>
<th>{$total|number_format|escape} �Х���</th>
</tr>
</table>

