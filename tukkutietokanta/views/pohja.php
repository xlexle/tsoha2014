<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="../css/bootstrap.css" rel="stylesheet">
        <link href="../css/bootstrap-theme.css" rel="stylesheet">
        <link href="../css/main.css" rel="stylesheet">
        <title></title>
    </head>
    <body>
        <?php if (!empty($data->error)): ?>
            <div class="alert alert-danger">
                <?php echo $data->error; ?>
            </div>
        <?php elseif (!empty($data->success)): ?>
            <div class="alert alert-success">
                <?php echo $data->success; ?>
            </div>
        <?php endif; ?>

        <?php require 'views/header.php'; ?>
        <?php require 'views/' . $sivu . '.php'; ?>
    </body>
</html>