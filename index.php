<?php
/**
 * Opendrop.
 *
 * @author   Patrick Schroen / https://github.com/pschroen
 * @license  MIT Licensed
 */

// Change the following settings
$S3_BUCKET = 'http://s3.amazonaws.com/opendrop';

$files = array();
$media = '';
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
    <title>Opendrop - Share files instantly</title>
    <meta name="title"                                  content="Opendrop - Share files instantly">
    <meta name="description"                            content="Removes uploads after 7 days and includes a built-in HTML5 media player. Perfect for sharing audio and video edits.">
    <meta name="keywords"                               content="file, sharing, drag, drop, box">
    <meta name="author"                                 content="Patrick Schroen">
    <meta name="viewport"                               content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-title"             content="Opendrop - Share files instantly">
    <meta name="apple-mobile-web-app-status-bar-style"  content="black-translucent">
    <meta property="description"                        content="Removes uploads after 7 days and includes a built-in HTML5 media player. Perfect for sharing audio and video edits.">
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
    <meta property="og:site_name"                       content="Opendrop - Share files instantly">
    <meta property="og:title"                           content="Opendrop - Share files instantly">
    <meta property="og:description"                     content="Removes uploads after 7 days and includes a built-in HTML5 media player. Perfect for sharing audio and video edits.">
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
            position: relative;
            margin: 0;
            background-color: #111;
            font-family: 'Open Sans', sans-serif;
            font-size: 13px;
            line-height: 18px;
            color: white;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.5);
            -webkit-user-select: none;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizelegibility;
        }
        a {
            color: white;
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
        .table {
            position: absolute;
            display: table;
            width: 100%;
            height: 100%;
        }
        .table-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
        }
        .background video {
            position: fixed;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            -webkit-transform: translateX(-50%) translateY(-50%);
            transform: translateX(-50%) translateY(-50%);
            /*background: url() no-repeat;*/
            background-size: cover;
            -webkit-user-select: none;
        }
        .controls {
            position: absolute;
            left: 10px;
            bottom: 10px;
            font-family: sans-serif;
            font-size: 12px;
            line-height: 16px;
            color: white;
            text-align: left;
            -webkit-user-select: none;
            opacity: 0;
        }
        .controls code {
            font-weight: bold;
            -webkit-user-select: none;
            cursor: default;
        }
        .controls span {
            height: 16px;
            margin-left: 10px;
            padding: 0 5px;
            background-color: white;
            border-radius: 3px;
            color: #222;
            text-shadow: none;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.5);
            white-space: nowrap;
            -webkit-user-select: none;
            cursor: default;
        }
        .controls.fadein code {
            -webkit-user-select: text;
            cursor: auto;
        }
        .controls.fadein span {
            cursor: pointer;
        }
        .legal {
            position: absolute;
            right: 10px;
            bottom: 10px;
            font-family: sans-serif;
            font-size: 12px;
            line-height: 16px;
            color: white;
            text-align: right;
        }
        .github {
            position: absolute;
            left: 0px;
            top: 0px;
        }
        .media.canplay::after {
            content: "►";
            padding-left: 10px;
        }

        .fadeout {
            -webkit-animation: fadeout 1s;
            animation: fadeout 1s;
            opacity: 0;
        }
        @-webkit-keyframes fadeout {
            0%   { opacity: 1; }
            100% { opacity: 0; }
        }
        @keyframes fadeout {
            0%   { opacity: 1; }
            100% { opacity: 0; }
        }

        .fadein {
            -webkit-animation: fadein 1s;
            animation: fadein 1s;
            opacity: 1;
        }
        @-webkit-keyframes fadein {
            0%   { opacity: 0; }
            100% { opacity: 1; }
        }
        @keyframes fadein {
            0%   { opacity: 0; }
            100% { opacity: 1; }
        }

        @media (max-width: 1023px) {
            .github {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="container fadein">
        <div class="table">
            <div class="table-cell">
<?php if (!empty($files)) { ?>
                <img src="assets/images/alienkitty.png" style="width: 100px; height: 100px;" alt="Opendrop - Share files instantly">
<?php } else { ?>
                <input type="file" id="files" name="files[]" multiple>
                <a href="#" id="opendrop"><img src="assets/images/alienkitty.png" style="width: 100px; height: 100px;" alt="Opendrop - Share files instantly"></a>
<?php } ?>
                <h2 class="percent"></h2>
                <div id="status">
<?php if (!empty($files)) { ?>
<?php foreach ($files as $file) {
if (strstr($file->type, 'audio') || strstr($file->type, 'video')) {
    $media = 'media';
}
?>
                    <h3><a href="<?php echo $S3_BUCKET.$_SERVER['REQUEST_URI'].'/'.$file->name; ?>"<?php if (!empty($media)) { ?> class="<?php echo $media; ?>"<?php } ?> target="_blank" data-name="<?php echo $file->name; ?>" data-type="<?php echo $file->type; ?>" data-size="<?php echo $file->size; ?>"><?php echo $file->name; ?> (<?php echo format_filesize($file->size, 1); ?>)</a></h3>
<?php } ?>
<?php } else { ?>
                    <h3>7 day drop</h3>
<?php } ?>
                </div>
            </div>
        </div>
        <div class="legal" draggable="false"><div><a href="https://twitter.com/opendrop" target="_blank">@opendrop</a></div><div>TM &amp; © UFO Technologies Ltd.</div><div><a href="http://blog.ufotechnologies.com/" target="_blank">Made with ❤ in Toronto, Canada</a></div></div>
        <a class="github" href="https://github.com/pschroen/opendrop" target="_blank" draggable="false"><img src="https://s3.amazonaws.com/github/ribbons/forkme_left_white_ffffff.png" alt="Fork me on GitHub"></a>
    </div>
<?php if (!empty($media)) { ?>
    <div class="controls"><code>00:00:00</code><span>esc</span><span>space</span><span>←</span><span>→</span></div>
<?php } ?>
<?php if (empty($files)) { ?>
    <script src="assets/js/app.js"></script>
<?php } elseif (!empty($media)) { ?>
    <script src="assets/js/media.js"></script>
<?php } ?>
</body>
</html>
