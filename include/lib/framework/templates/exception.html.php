<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Uncaught Exception</title>
	<meta name="robots" content="NONE,NOARCHIVE">
	<style type="text/css">
		html * { padding:0; margin:0; }
		body * { padding:10px 20px; }
		body * * { padding:0; }
		body { font:small sans-serif; background:#eee; }
		body>div { border-bottom:1px solid #ddd; }
		h1 { font-weight:normal; margin-bottom:.4em; }
		h1 span { font-size:60%; color:#666; font-weight:normal; }
		table { border:none; border-collapse: collapse; width:100%; }
		td, th { vertical-align:top; padding:2px 3px; }
		th { width:5em; text-align:right; color:#666; padding-right:.5em; }
		#info { background:#f6f6f6; }
		#info ol { margin: 0.5em 4em; }
		#info ol li { font-family: monospace; margin: 1em 0;}
		#summary { background: #ffc; }
		#explanation { background:#eee; border-bottom: 0px none; }
	</style>
</head>
<body>
<div id="summary">
	<h1><?= $exception->getMessage() ?> <span>on line <?= $exception->getLine() ?></span></h1>
	<table class="meta">
		<tr>
			<th>Method</th>
			<td><?= $_SERVER['REQUEST_METHOD'] ?></td>
		</tr>
		<tr>
			<th>Code</th>
			<td><?= $exception->getCode() ?></td>
		</tr>
		<tr>
			<th>File</th>
			<td><a href="txmt://open/?line=<?= $exception->getLine() ?>&amp;url=file://<?= $exception->getFile() ?>">
			    <?= $exception->getFile() ?></a></td>
		</tr>
		<tr>
			<th>Line</th>
			<td><?= $exception->getLine() ?></td>
		</tr>
	</table>
</div>
<div id="info">
	<p></p>
	<ol>
<?		foreach($exception->getTrace() as $trace): ?>
		<li>
			<p>
				<? if(!empty($trace['class'])): ?><strong><?= $trace['class'] ?></strong><? endif; ?>
				<? if(!empty($trace['type'])): ?><?= $trace['type'] ?><? endif; ?>
				<? if(!empty($trace['function'])): ?><strong><?= $trace['function'] ?></strong><? endif; ?>
				<? if(!empty($trace['args'])): ?>( <?
					foreach($trace['args'] as $arg):
						?><?= gettype($arg) ?>, <?
					endforeach;
				?> )<? endif; ?>
			</p>
			<? if(!empty($trace['line'])): ?>
			<strong>Line <?= $trace['line'] ?></strong> : 
			<a href="txmt://open/?line=<?= $trace['line'] ?>&amp;url=file://<?= $trace['file'] ?>">
			    <?= $trace['file'] ?></a>
			<? endif; ?>
		</li>
<?		endforeach; ?>
	</ol>
</div>

<div id="explanation">
	<p></p>
</div>
</body>
</html>