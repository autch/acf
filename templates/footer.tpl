<hr/>
{if !empty($usage)}
  処理時間：{$usage.time|escape} 秒、
  使用メモリ：{$usage.memory|escape} K バイト<br />
{/if}
<p>I'm a footer.  Find me in {$smarty.const.TF_SMARTY_TEMPLATES}footer.tpl</p>

</body>
</html>
