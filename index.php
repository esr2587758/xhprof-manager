<?php
$d = dir('./data');
$list = array();
while (false !== ($entry = $d->read())) {
   $list[] = $entry;
}
sort($list);
array_shift($list);
array_shift($list);
$d->close();

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!-- 最新 Bootstrap 核心 CSS 文件 -->
		<link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap.min.css">
		<!-- 可选的Bootstrap主题文件（一般不用引入） -->
		<link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap-theme.min.css">
		<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
		<script src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
		<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
		<script src="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
	</head>
	<body>
		<h1>xhprof 性能调试</h1>
		<?php if(!empty($list)) :?>
		<table class="table table-striped">
			<?php foreach ($list as $key => $value) :?>
				<tr>
					<td>
						<a target="_blank" href="/run.php?action=view&name=<?php echo $value;?>"><?php echo $value;?></a>
						<a onclick="return confirm('Are you sure?')" href="/run.php?action=delete&name=<?php echo $value;?>">删除</a>
					</td>
				</tr>
			<?php endforeach;?>
		</table>
		<?php endif;?>
		
	</body>
</html>
