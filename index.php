<?php
require_once "Parsedown.php";
require_once "RenderConfig.php";
require_once "ModifyElements.php";
require_once "ParsedownModify.php";

parse_str($_SERVER["QUERY_STRING"], $query_array);
if(array_key_exists("cfg", $query_array)) {
    $choice = $query_array["cfg"];
} else $choice = "test";

$cfgfile = $choice . ".json";

$pageconfig = new RenderConfig($cfgfile);
$ElementModifier = new ModifyElements($pageconfig);
$render = new ParsedownModify($ElementModifier);

global $g_mdpage;
$mdopt = "./mdrenderpage.json";
if(file_exists($mdopt))
{
    $g_mdpage = json_decode(file_get_contents($mdopt));
}

// generate a static file?
if($pageconfig->genstatic === true) ob_start();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
    if($pageconfig->metadesc !== "") echo "    <meta name=\"description\" content=\"$pageconfig->metadesc\"/>\n";
    if($pageconfig->metakeyw !== "") echo "    <meta name=\"keywords\" content=\"$pageconfig->metakeyw\"/>\n";
    if($pageconfig->metaauth !== "") echo "    <meta name=\"author\" content=\"$pageconfig->metaauth\"/>\n";
?>

    <title><?php echo $pageconfig->pagetitle; ?></title>
    <!-- Bootstrap / fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700">
    <!-- page styling -->
    <link rel="stylesheet" href="./assets/css/document.css">

<?php
    if(isset($g_mdpage->totop) && ($g_mdpage->totop === true))
    {
        echo '<link rel="stylesheet" href="./assets/css/totop.css">';
    }

    if(isset($g_mdpage->socicon) && ($g_mdpage->socicon === true))
    {
        echo '<link rel="stylesheet" href="./assets/css/socicon.css">';
    }
?>
    <!-- jquery, etc -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body class="page-nocopy container-fluid">
    <!-- Document -->
    <div class="row doc-body">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
            <!-- Rendered Document -->
<?php
$file = file_get_contents($pageconfig->mdfile, true);
echo "\n" . $render->text($file) . "\n";
?>
            <!-- ^Rendered Document -->
        </div>
<?php
    if(isset($g_mdpage->totop) && ($g_mdpage->totop === true))
    {
        echo '        <button id="gototop" class="gototop" onclick="jumpToTop()" title="Go to top of page">&#9650;<br>top</button>' . "\n";
    }
?>
        <br>
        <br>
    </div>
    <!-- ^Document -->
    <!-- Page Footer -->
<?php
    if(isset($g_mdpage->footer) && ($g_mdpage->footer === true))
    {
?>
    <footer class="navbar navbar-default navbar-fixed-bottom">
        <div class="text-center">
            <div class="mdfooter col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12">
                <p>
<?php
    if(isset($g_mdpage->socicon) && ($g_mdpage->socicon === true))
    {
        echo "                    <a href=".$g_mdpage->socitems[0]->url." target=".$g_mdpage->socitems[0]->target." class=".$g_mdpage->socitems[0]->class." title=".$g_mdpage->socitems[0]->title."></a>\n";
        if(isset($g_mdpage->footertxt))
        {
            echo "                    " . $g_mdpage->footertxt . "\n";
        }
        echo "                    <a href=".$g_mdpage->socitems[1]->url." target=".$g_mdpage->socitems[1]->target." class=".$g_mdpage->socitems[1]->class." title=".$g_mdpage->socitems[1]->title."></a>\n";
    }
?>
                </p>
            </div>
        </div>
    </footer>
<?php   
    }
?>
    <!-- ^Page Footer -->
</body>
<!-- code for the "go to top" button -->
<script src="./assets/js/totop.js"></script>
</html>
<?php
// generate a static file?
if($pageconfig->genstatic === true) {
    $content = ob_get_contents();
    ob_end_flush();
    $fh = fopen("$pageconfig->statname",'w'); 
    fwrite($fh,$content);
    fclose($fh);
}

