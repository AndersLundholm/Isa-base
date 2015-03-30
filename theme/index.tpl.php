<!DOCTYPE html>
<html lang="<?=$lang?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php if(isset($favicon)): ?><link rel="icon" type="image/png" href="<?=$favicon?>"><?php endif; ?>
    <?php foreach($stylesheets as $stylesheet): ?>
    <link rel="stylesheet" type="text/css" href="<?=$stylesheet?>">
    <?php endforeach; ?>
    <title><?=get_title($title)?></title>
</head>
<body>
    <div class="wrapper blue">
        <header class="mainHeader">
            <div class="innerHeader">
            <?php 
                echo $header; 
                if(isset($menu)) { echo generate_menu($menu); } ?>
            </div>
        </header>
        <div class="mainContainer">
            <div class="mainContent">
                <?=$main;?>
            </div>
        </div>
    </div>
    <footer class="mainFooter">
        <?=$footer?>
    </footer>             
</body>
</html>
