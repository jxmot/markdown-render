<?php
global $g_pageconfig;
$ogopt = null;
if(($g_pageconfig->genstatic === true) && ($g_pageconfig->oghead === true)) {

    $ogopt = json_decode(file_get_contents($g_pageconfig->ogjson));

    echo "<head prefix=\"$ogopt->prefix\">\n\n";

    echo "    <!-- Twitter Card data -->\n";
    echo "    <meta name=\"twitter:card\" content=\"".$ogopt->twitter->card."\"/>\n";
    echo "    <meta name=\"twitter:site\" content=\"".$ogopt->twitter->site."\"/>\n";
    echo "    <meta name=\"twitter:title\" content=\"".$ogopt->twitter->title."\"/>\n";
    echo "    <meta name=\"twitter:url\" content=\"".$ogopt->twitter->url."\"/>\n";
    echo "    <meta name=\"twitter:description\" content=\"".$ogopt->twitter->description."\"/>\n";
    echo "    <meta name=\"twitter:creator\" content=\"".$ogopt->twitter->creator."\"/>\n";
    echo "    <!-- Twitter summary card with large image must be at least 280x150px -->\n";
    if($ogopt->twitter->use_ph === true) {
        if(isset($ogopt->twitter->text_ph)) $txtarg = "?text=" . str_replace(" ", "%20", $ogopt->twitter->text_ph);
        else $txtarg = "";
        $ogimage = $ogopt->twitter->image_ph . "/" . $ogopt->twitter->imagewidth_ph . "x" . $ogopt->twitter->imageheight_ph . "." . $ogopt->twitter->imagetype_ph . "/" . $ogopt->twitter->bgcolor_ph . "/" . $ogopt->twitter->fgcolor_ph . $txtarg;
        echo "    <meta name=\"twitter:image\" content=\"".$ogimage."\"/>\n";
        echo "    <meta name=\"twitter:image:width\" content=\"".$ogopt->twitter->imagewidth_ph."\"/>\n";
        echo "    <meta name=\"twitter:image:height\" content=\"".$ogopt->twitter->imageheight_ph."\"/>\n";
    } else {
        echo "    <meta name=\"twitter:image\" content=\"".$ogopt->twitter->image."\"/>\n";
        echo "    <meta name=\"twitter:image:width\" content=\"".$ogopt->twitter->imagewidth."\"/>\n";
        echo "    <meta name=\"twitter:image:height\" content=\"".$ogopt->twitter->imageheight."\"/>\n";
    }
    echo "\n\n";

    echo "    <!-- facebook and linkedin... -->\n";
    if($ogopt->og->use_ph === true) {
        if(isset($ogopt->og->text_ph)) $txtarg = "?text=" . str_replace(" ", "%20", $ogopt->og->text_ph);
        else $txtarg = "";
        $ogimage = $ogopt->og->image_ph . "/" . $ogopt->og->imagewidth_ph . "x" . $ogopt->og->imageheight_ph . "." . $ogopt->og->imagetype_ph . "/" . $ogopt->og->bgcolor_ph . "/" . $ogopt->og->fgcolor_ph . $txtarg;
        echo "    <meta property=\"og:image\" content=\"".$ogimage."\"/>\n";
        echo "    <meta property=\"og:image:width\" content=\"".$ogopt->og->imagewidth_ph."\"/>\n";
        echo "    <meta property=\"og:image:height\" content=\"".$ogopt->og->imageheight_ph."\"/>\n";
        echo "    <meta property=\"og:image:type\" content=\"image/".$ogopt->og->imagetype_ph."\"/>\n";
    } else {
        echo "    <meta property=\"og:image\" content=\"".$ogopt->og->image."\"/>\n";
        echo "    <meta property=\"og:image:width\" content=\"".$ogopt->og->imagewidth."\"/>\n";
        echo "    <meta property=\"og:image:height\" content=\"".$ogopt->og->imageheight."\"/>\n";
        echo "    <meta property=\"og:image:type\" content=\"".$ogopt->og->imagetype."\"/>\n";
    }

    echo "    <meta property=\"og:url\" content=\"".$ogopt->og->url."\"/>\n";
    echo "    <meta property=\"og:title\" content=\"".$ogopt->og->title."\"/>\n";
    echo "    <meta property=\"og:description\" content=\"".$ogopt->og->description."\"/>\n";
    echo "    <meta property=\"og:type\" content=\"".$ogopt->og->type."\"/>\n";
    echo "    <meta property=\"og:site_name\" content=\"".$ogopt->og->site_name."\"/>\n";

} else {
    echo "<head>\n";
}
echo "\n";
?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
    if(isset($g_pageconfig->metadesc)) echo "    <meta name=\"description\" content=\"".$g_pageconfig->metadesc."\"/>\n";
    if(isset($g_pageconfig->metakeyw)) echo "    <meta name=\"keywords\" content=\"".$g_pageconfig->metakeyw."\"/>\n";
    if(isset($g_pageconfig->metaauth)) echo "    <meta name=\"author\" content=\"".$g_pageconfig->metaauth."\"/>\n";
?>

    <title><?php echo $g_pageconfig->pagetitle; ?></title>

    <!-- Bootstrap / fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700">
    <!-- page styling -->
    <link rel="stylesheet" href="./assets/css/document.css">

<?php
    if(isset($g_mdpage->totop) && ($g_mdpage->totop === true))
    {
        echo '    <link rel="stylesheet" href="./assets/css/totop.css">'."\n";
    }

    if(isset($g_mdpage->socicon) && ($g_mdpage->socicon === true))
    {
        echo '    <link rel="stylesheet" href="./assets/css/socicon.css">'."\n";
    }
?>
    <!-- jquery, etc -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

