<?php
// if delete click
$dir = __DIR__;
if ($_GET['action']) {
    $d = $dir . '/' . $_GET['file'];
    unlink($d);
}
// scane videos dir
$files = scandir($dir);
?>
<html>
    <head>
        <title>Youtube Downloader</title>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <style>
            .videos {
                width: 500px;
                margin: 40px auto 0 auto;
            }
        </style>
    </head>
    <body>
        <div class="videos">
            <h2>
                Downloaded Videos
            </h2>
            <table class="table table-striped table-bordered">
                <tr>
                    <th>
                        Dowland
                    </th>
                    <th>
                        Delete
                    </th>
                </tr>
                <?php
                foreach ($files As $file) {

                    if (!in_array($file, array('index.php', '.', '..', 'error_log'))) {
                        echo '<tr><td><a href="' . $file . '" >' . $file . '</a></td>';
                        echo '<td><a href="index.php?action=delete&file=' . $file . '"> Delete </a></td>';
                        echo '</tr>';
                    }
                }
                ?>
            </table>
        </div>
    </body>
</html>

