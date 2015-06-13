<?php
/**
 * OpenDrop.
 *
 * @author   Patrick Schroen / https://github.com/pschroen
 * @license  MIT Licensed
 */

// Change the following settings
$S3_BUCKET = 'http://s3.amazonaws.com/opendrop';

$files = array();
if ($_SERVER['REQUEST_URI'] == '/') {
    function random_drop() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
        srand((double)microtime()*1000000);
        $drop = '';
        for ($i = 0; $i < 4; $i++) $drop = $drop.substr($chars, rand()%63, 1);
        $url = $GLOBALS['S3_BUCKET'].'/'.$drop;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        print $S3_BUCKET;
        return $httpcode >= 400 ? $drop : random_drop();
    }
    $box = random_drop();
    header('Location: /'.$box);
    die();
} else {
    $url = $S3_BUCKET.$_SERVER['REQUEST_URI'].'/filelist.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpcode == 200) {
        function format_filesize($B, $D = 2) {
            $S = 'BkMGTPEZY';
            $F = floor((strlen($B)-1)/3);
            return sprintf("%.{$D}f", $B/pow(1024, $F)).' '.@$S[$F].'B';
        }
        $files = json_decode($data);
    }
}
?>
<!doctype html>
<!--
                                 __                         
                                /\ \                        
  ___   _____      __    ___    \_\ \  _ __   ___   _____   
 / __`\/\ '__`\  /'__`\/' _ `\  /'_` \/\`'__\/ __`\/\ '__`\ 
/\ \L\ \ \ \L\ \/\  __//\ \/\ \/\ \L\ \ \ \//\ \L\ \ \ \L\ \
\ \____/\ \ ,__/\ \____\ \_\ \_\ \___,_\ \_\\ \____/\ \ ,__/
 \/___/  \ \ \/  \/____/\/_/\/_/\/__,_ /\/_/ \/___/  \ \ \/ 
          \ \_\                                       \ \_\ 
           \/_/                                        \/_/ 
                                                            
                               >>opendrop.io. //+o: opendrop
-->
<html>
<head>
    <meta charset="utf-8">
    <title>OpenDrop</title>
    <meta name="title"                                  content="OpenDrop">
    <meta name="description"                            content="Share files instantly">
    <meta name="keywords"                               content="file, sharing, drag, drop, box">
    <meta name="author"                                 content="Patrick Schroen">
    <meta name="viewport"                               content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-title"             content="OpenDrop">
    <meta name="apple-mobile-web-app-status-bar-style"  content="black-translucent">
    <meta property="description"                        content="Share files instantly">
    <meta property="copyright"                          content="Copyright © UFO Technologies Ltd. All rights reserved.">
    <link rel="shortcut icon"                           href="favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon"                        href="assets/images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="57x57"          href="assets/images/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72"          href="assets/images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76"          href="assets/images/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114"        href="assets/images/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120"        href="assets/images/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144"        href="assets/images/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152"        href="assets/images/apple-touch-icon-152x152.png">
    <meta property="og:type"                            content="Website">
    <meta property="og:site_name"                       content="OpenDrop">
    <meta property="og:title"                           content="OpenDrop">
    <meta property="og:description"                     content="Share files instantly">
    <meta property="og:url"                             content="http://opendrop.io/">
    <meta property="og:image"                           content="assets/images/alienkitty.png">
    <meta property="og:image:type"                      content="image/png">
    <style>
        @font-face {
            font-family: 'Open Sans';
            src: url('assets/fonts/OpenSans-Regular-webfont.eot');
            src: url('assets/fonts/OpenSans-Regular-webfont.eot?#iefix') format('embedded-opentype'),
                 url('assets/fonts/OpenSans-Regular-webfont.woff') format('woff'),
                 url('assets/fonts/OpenSans-Regular-webfont.ttf') format('truetype'),
                 url('assets/fonts/OpenSans-Regular-webfont.svg#OpenSansRegular') format('svg');
            font-weight: normal;
            font-style: normal;
        }
        html, body {
            height: 100%;
            overflow: hidden;
        }
        body {
            background-color: #111;
            margin: 0;
            font-family: 'Open Sans', sans-serif;
            font-size: 13px;
            line-height: 18px;
            color: #f8f8f0;
            position: relative;
            -webkit-user-select: none;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizelegibility;
        }
        a {
            color: #ccc;
            text-decoration: none;
        }
        h1 {
            font-size: 24px;
            line-height: 28px;
        }
        h2 {
            font-size: 20px;
            line-height: 24px;
        }
        h3 {
            font-size: 18px;
            line-height: 22px;
        }
        input {
            display: none;
        }
        #table {
            position: absolute;
            display: table;
            width: 100%;
            height: 100%;
        }
        #table-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .legal {
            position: absolute;
            right: 10px;
            bottom: 10px;
            font-family: sans-serif;
            font-size: 12px;
            line-height: 16px;
            color: #ccc;
            text-align: right;
        }
        .github {
            position: absolute;
            left: 0px;
            top: 0px;
        }

        @media (max-width: 1023px) {
            .github {
                display: none;
            }
        }
    </style>
</head>
<body>
<div id="table">
    <div id="table-cell">
<?php if (!empty($files)) { ?>
        <img src="assets/images/alienkitty.png" style="width: 100px; height: 100px;" alt="OpenDrop">
<?php } else { ?>
        <input type="file" id="files" name="files[]" multiple>
        <a href="#" id="opendrop"><img src="assets/images/alienkitty.png" style="width: 100px; height: 100px;" alt="OpenDrop"></a>
<?php } ?>
        <h2 class="percent"></h2>
        <div id="status">
<?php if (!empty($files)) { ?>
<?php foreach ($files as $file) { ?>
            <h3><a href="<?php echo $S3_BUCKET.$_SERVER['REQUEST_URI'].'/'.$file->name; ?>" target="_blank"><?php echo $file->name; ?> (<?php echo format_filesize($file->size, 1); ?>)</a></h3>
<?php } ?>
<?php } else { ?>
            <h3>24hr drop</h3>
<?php } ?>
        </div>
    </div>
</div>
</body>
<?php if (empty($files)) { ?>
<script src="assets/js/app.js"></script>
<?php } ?>
<div class="legal" draggable="false"><div><a href="https://twitter.com/OpenDrop" target="_blank">@OpenDrop</a></div><div>TM &amp; © UFO Technologies Ltd.</div><div><a href="http://blog.ufotechnologies.com/" target="_blank">Made with ❤ in Toronto, Canada</a></div></div>
<a class="github" href="https://github.com/pschroen/opendrop" target="_blank" draggable="false"><img src="https://s3.amazonaws.com/github/ribbons/forkme_left_white_ffffff.png" alt="Fork me on GitHub"></a>
</html>
