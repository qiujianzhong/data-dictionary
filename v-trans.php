<meta charset="utf-8">
<title><?php echo MYSQL_DB; ?>-Dictionary-Translate</title>
<style>
.pure-table{border-collapse:collapse;border-spacing:0;empty-cells:show;border:1px solid #cbcbcb}.pure-table caption{color:#000;font:italic 85%/1 arial,sans-serif;padding:1em 0;text-align:center}.pure-table td,.pure-table th{border-left:1px solid #cbcbcb;border-width:0 0 0 1px;font-size:inherit;margin:0;overflow:visible;padding:.5em 1em}.pure-table thead{background-color:#e0e0e0;color:#000;text-align:left;vertical-align:bottom}.pure-table td{background-color:transparent}.pure-table-odd td{background-color:#f2f2f2}.pure-table-striped tr:nth-child(2n-1) td{background-color:#f2f2f2}.pure-table-bordered td{border-bottom:1px solid #cbcbcb}.pure-table-bordered tbody>tr:last-child>td{border-bottom-width:0}.pure-table-horizontal td,.pure-table-horizontal th{border-width:0 0 1px 0;border-bottom:1px solid #cbcbcb}.pure-table-horizontal tbody>tr:last-child>td{border-bottom-width:0}
</style>

<script type="text/javascript">
	function formSubmit() {
		var s = {}
		document.querySelectorAll('.ip').forEach(function (t) {
		    s[t.name] = t.value
		})

		document.querySelector('#postbody').value = JSON.stringify(s)

		document.querySelector("#myForm").submit()
	}
</script>

<form id="myForm" action="?q=trans&t=<?php echo time();?>" method="POST">
	<input type="hidden" name="postbody" id="postbody">
</form>
	<table class="pure-table">

	<caption>Manual translate <?php echo MYSQL_DB; ?>'s dictionary (<input type="button" id="x" value="Submit (提交修改)" onclick="formSubmit()">)</caption>

	<tbody>

<?php $i=0;foreach (G::$dicts as $key => $val):?>
	
	<tr<?php if ((++$i)%2):?> class="pure-table-odd"<?php endif;?>>
		<td><?php echo htmlspecialchars($key); ?></td>	
		<td>
			<?php //if (strlen($key))?>
			<input class='ip' type="text" name="<?php echo md5($key);?>" size="240" value="<?php echo $val;?>"></td>
	</tr>

<?php endforeach;?>

	
	</tbody>
</table>


