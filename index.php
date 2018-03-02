<?php
require_once "Parsedown.php";
require_once "RenderConfig.php";
require_once "ModifyElements.php";
require_once "ParsedownModifyVoid.php";

$pageconfig = new RenderConfig("./ESP8266-RCWL0516.json");
$ElementModifier = new ModifyElements($pageconfig);
$ModifyParsedown = new ParsedownModifyVoid($ElementModifier);
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700">

    <link rel="stylesheet" href="./assets/css/document.css">
    <link rel="stylesheet" href="./assets/css/totop.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body class="page-nocopy">
    <!-- Document -->
    <div class="row doc-body">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
<?php
$file = file_get_contents($pageconfig->mdfile, true);
echo $ModifyParsedown->text($file);
?>
        </div>
        <button id="gototop" class="gototop" onclick="jumpToTop()" title="Go to top of page">&#9650;<br>top</button> 
        <br>
        <br>
    </div>
    <!-- ^Document -->
    <!-- Page Footer -->
    <footer class="navbar navbar-default navbar-fixed-bottom">
        <div class="container text-center">
            <p class="navbar-text col-lg-12 col-md-12 col-sm-12 col-xs-12">
                2017 &copy; James Motyl
            </p>
        </div>
    </footer>
    <!-- ^Page Footer -->
</body>
<script src="./assets/js/totop.js"></script>
</html>
