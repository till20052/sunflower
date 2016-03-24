<style type="text/css">
	#tracer-box {
		background: #fff;
		font-family: 'Courier', monospace;
		font-size: 12px;
		color: #444;
		border: 1px solid #ccc;
		padding: 10px;
	}

	#tracer-box .error_msg {
		font-size: 14px;
	}

	#tracer-box .open_stack {
		margin-top: 10px;
	}

	#tracer-box .open_stack a {
		padding-right: 10px;
	}

	#tracer-box .stack {
		border: 1px solid #ccc;
		padding: 10px;
		margin-top: 10px;
		background: #eee;
	}

	#tracer-box .stack table tr td {
		font-size: 12px;
		padding-right: 10px;
	}
</style>

<div id="tracer-box">

	<div class="error_msg">
		ERROR<? if($code > 0){ ?> [<?=$code?>]<? } ?>: <?=$error_msg?>
	</div>

	<div id="stack" class="stack">
		<table cellpadding="0" cellspacing="0">
			<? $cnt = 0 ?>
			<? foreach($trace as $item){ ?>
				<tr>
					<td align="center"><?=$item['num']?>.</td>
					<td><?=$item['call']?>(<?=$item['args']?>);</td>
					<td>line: <?=$item['line']?></td>
					<td>in <?=$item['file']?></td>
				</tr>
			<? } ?>
		</table>
	</div>

	<div class="open_stack">
		<a href="javascript:;" onclick="document.getElementById('stack').style.display = 'block';">Open stack</a>
		<a href="javascript:;" onclick="document.getElementById('stack').style.display = 'none';">Close stack</a>
	</div>

</div>
