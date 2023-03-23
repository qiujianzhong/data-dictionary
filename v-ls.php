<meta charset="utf-8">
<title><?php echo MYSQL_DB; ?>-Data-Dictionary</title>
<style>
table{ border-collapse:collapse;border:1px solid #CCC;background:#efefef;width:100%; margin-bottom:1em;}
table th{ text-align:left; font-weight:bold; padding:.5em 2em .5em .75em; line-height:1.6em; font-size:12px; border:1px solid #CCC;}
table td{ padding:.5em .75em; line-height:1.6em; font-size:12px; border:1px solid #CCC;background-color:#fff;}
.c1{ width: 150px;}
.c2{ width: 120px;}
.c3{ width: 130px;}
.c4{ width: 130px;}
caption{ font-size:14px; font-weight:bold; line-height:2em; text-align:left; }
a {text-decoration: none;color: blue;}
</style>
<center>
<h2 style="color: gold;"><?php echo MYSQL_DB; ?> (<a href="?q=trans" target="_blank">手动修改备注</a>) </h2>
</center>
<?php
$sql="SHOW TABLE STATUS FROM " . MYSQL_DB . " WHERE  Engine is not null";
$tables = G::$ds->all($sql);

// 添加链接锚点
$a_list = array();
foreach ($tables as $k=>$table){
	$name = is_callable('g_table_filter') ? g_table_filter($table['Name']) : $table['Name'];
	if ( !empty($name) ) $a_list[] = $name;
	else
	{
		unset($tables[$k]);
	}
}

$a_list = array_chunk($a_list,5);
$out = "<a name=\"__top__\"></a>";
$out .= '<table><caption style="color: #CCC;">show tables</caption><tbody>';
foreach ($a_list as $items){
	$out .= '<tr>';
	
	foreach ($items as $item){
		$out .= "<td class=\"c3\"><a href=\"#{$item}\">{$item}</a></td>";
	}
	
	$out .= '</tr>';
}

$out .= '</tbody></table>';
unset($a_list);
echo $out;
?>

<?php
$out = '';

foreach ($tables as $table){
	$out .= "<a name=\"{$table['Name']}\"></a>";
	$out .= '<table><caption>'.$table['Name'].'('. $table['Comment'] . g_trans_table($table['Name'], $table['Comment']) .')<span style="float: right;"><a href="#__top__">Top</a></span></caption><tbody>';
	$out .= '<tr><th>字段名</th><th>数据类型</th><th>允许空值</th><th>默认值</th><th>索引类型</th><th>额外</th><th>备注</th><th>备注翻译</th></tr>';
	
    $sql_tab="show full fields from `{$table['Name']}`";

    //echo '<hr>' . $sql_tab;

    $fields= G::$ds->all($sql_tab);

    // print_r($fields);
    foreach ($fields as $field){
		$out .= '<tr>
		<td class="c1">'.$field['Field'].'</td>
		<td class="c2">'.$field['Type'].'</td>
		<td class="c2">'.$field['Null'].'</td>
		<td class="c3">'.$field['Default'].'</td>
		<td class="c3">'.$field['Key'].'</td>		
		<td class="c3">'.$field['Extra'].'</td>
		<td class="c4 xd1">'.$field['Comment'].'</td>
		<td class="c4 xd2">' . g_trans_field($field['Field'],$field['Comment']).'</td>
		</tr>';
    }
	$out .= '</tbody></table>';
}
echo $out;
?>
