<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="<?= SITE_ROOT ?>/media/css/admin.css" type="text/css" charset="utf-8">
    <script src="<?= SITE_ROOT ?>/media/js/lib/prototype.js" type="text/javascript" charset="utf-8"></script>
    
    <title><?= $_ENV['CONFIG']['admin']['title'] ?> Administration</title>
</head>
<body class="<?= str_replace('/', '_', $controller->get_path_name()) ?>">
    <div id="container">
        <div id="header">
            <h1><?= $_ENV['CONFIG']['admin']['title'] ?> Administration</h1>
            <div id="nav">
                <ul>
                    <li class="active"><a href="<?= SITE_ROOT ?>/admin">Vehicles</a></li>
                </ul>
            </div>
        </div>

        <div id="content">
<?      if(!empty($errors) && count($errors)): ?>
            <ul class="errors">
<?          foreach($errors as $error): ?>
                <li><?= $error ?></li><?
            endforeach; ?>
            </ul>
<?      endif; ?>

<?      if(!empty($messages) && count($messages)): ?>
            <ul class="messages">
<?          foreach($messages as $message): ?>
                <li><?= $message ?></li><?
            endforeach; ?>
            </ul>
<?      endif; ?>

            <?= $yield ?>
        </div>
    </div>

    <div id="footer">
        <a href="http://realpie.com" target="_blank">
            <img src="<?= SITE_ROOT ?>/media/images/admin/rp_logo.gif" width="36" height="36">
        </a>
    </div>
</body>
</html>
