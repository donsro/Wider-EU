<?php
    define("ROOT", dirname(__FILE__));
    require_once(ROOT."/sitemap.php");
    function init() {
        if (!defined("PATH")) { define("PATH", "./"); }
    }
    function lastModified($filename) {
        if(file_exists(PATH.$filename)) {
            if(date(filemtime(PATH.$filename)) + 5*24*60*60 > time()) {
                $newCSS = " updated";
            }
            echo "<aside class=\"last-modified".$newCSS."\">"
            .date("F d Y", filemtime(PATH.$filename)) // "F d Y H:i:s."
            ."</aside>\n";
        }
    }
    function addArticle($filename, $articleId, $addLastModified) {
        if(file_exists(PATH.$filename)) {
            echo "\n<article id=\"$articleId\">\n";
            if($addLastModified) { lastModified(basename(PATH.$filename)); }
            include(PATH.$filename);
            echo "\n</article>\n";
        }
    }
    function printArticles($a) {
        foreach($a as $k => $v) {
            $addDate = true;
            $delimiter = "|";
            if(strpos($v, $delimiter) !== false) {
                $params = explode($delimiter, $v);
                $trimmed_params = array_map("trim", $params);
                if(in_array("no-date", $trimmed_params)) {
                    $addDate = false;
                }
            }
            addArticle($k.".php", $k, $addDate);
        }
    }
    init();
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Wider Europe - Listen Live</title>
        <link rel="stylesheet" href="/assets/css/foundation.css" />
        <link rel="stylesheet" href="/assets/css/widereu.css" />
        <script src="/assets/js/vendor/modernizr.js"></script>
        <style type="text/css">
            @media only screen and (max-width:40em) {
                header .hero-unit { padding:.4em 0 }
                header .hero-unit, header .hero-unit h1 { background-image: none }
                header .hero-unit h1, header .hero-unit h2 { padding: 0; margin: 0; line-height: 1.5em; font-size: 1.6em; float: left }
                header .hero-unit h1 { margin-right:.4em }
            }
            header .hero-unit h1 { font-size: 2em; padding: 30px 0 10px; line-height: 1em }
            header .hero-unit h1, header .hero-unit h1 a { color: #01A9DB }
            header .hero-unit h2 { font-size: 3.6em; color: #FFF; padding: 0 0 30px; margin-top: -10px; line-height: 1em }
            .last-modified { margin-top: 0 }
            .play-label { margin-bottom: .2em; font-style: italic; color: gray }
            .jp-controls { margin-left: 0; margin-bottom: 0 }
            .jp-controls .button { letter-spacing: .1em }
            .wait-a-moment { margin-left: 20px }
            .wait-a-moment img { margin-right: 10px }
            .djp-pause { display: none }
            audio { margin-bottom: 1em }
        </style>
    </head>
    <body>
        <section class="main-section">
            <header>
                <section class="row hero-unit">
                    <div class="large-12 columns">
                        <h1><a href="/">Wider Europe</a></h1>
                        <h2>Listen Live</h2>
                    </div>
                </section>
            </header>
            <div class="row">
                <div class="medium-9 columns">
                    <section class="topics">
                        <?php printArticles($sitemap[$current]["submenu"]); ?>
                    </section>
                </div>
                <div class="medium-3 columns show-for-medium-up">
                    <div class="amazon-iframe right">
                        <iframe src="http://rcm-eu.amazon-adsystem.com/e/cm?t=wideeuro03-21&o=2&p=14&l=ur1&category=books&banner=1WWN136WPFJ146PKPAR2&f=ifr" width="160" height="600" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
            <section class="row show-for-small-only">
                <div class="small-12 columns">
                    <iframe src="http://rcm-eu.amazon-adsystem.com/e/cm?t=wideeuro03-21&o=2&p=12&l=ur1&category=books&banner=12R7KJE43D3A8HWES702&f=ifr" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
                </div>
            </section>
        </section>

        <footer>
            <div class="row">
                <div class="large-12 columns">
                    <p>&copy; 2014 Wider Europe Limited - contact: enquiries@widereurope.eu</p>
                </div>
            </div>
        </footer>

        <script src="/assets/js/vendor/jquery.js"></script>
        <script src="/assets/js/foundation.min.js"></script>
        <script>
            $(document).foundation();
            // $.ajaxSetup({ cache: false });
            $(document).ready( function(){
                updateSongInfo();
            });
            function updateSongInfo() { loadMusicInfo(); window.setInterval(checkModified, 15000) }
            function checkModified() {
                $.ajax({
                    method: "HEAD",
                    url: "musicLogs/song.xml",
                    ifModified: true, // forces check with server
                    success: function (result, textStatus, jqXHR) {
                        if (jqXHR.status === 200) {
                            loadMusicInfo();
                        }
                    }
                });
            }
            function loadMusicInfo() {
                $.ajax({
                    url: "musicLogs/song.xml",
                    dataType: "xml",
                    success: function (xml) {
                        var songTitle = $(xml).find("item").first().find("title").text();
                        $("#performer").text(songTitle);
                    },
                    error: function() {
                        $("#performer").text("Song title not available");
                    }
                });
                $("#justPlayed").load("musicLogs/playing.txt");
            }
        </script>
    </body>
</html>