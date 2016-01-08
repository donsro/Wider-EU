<?php
    define("ROOT", dirname(__FILE__));
    require_once(ROOT."/sitemap.php");
    $isLandingPage = isset($landingPage) && $landingPage ? TRUE : FALSE;
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
    /*
    function printToColumns($a, $i) {
        $arraySize = count($a);
        $columnSize = $arraySize % 2 == 0 ? $arraySize / 2 : ++$arraySize / 2;        
        $a1 = isset($i) && $i == 1 ? array_slice($a, 0, $columnSize) : array_slice($a, $columnSize);
        printArticles($a1);
    }
    */
    function addLinks($a, $path) {
        $delimiter = "|";
        $i = 0;
        foreach($a as $k => $v) {
            if(strpos($v, $delimiter) !== false) {
                $params = explode($delimiter, $v);                
                $v = trim($params[0]);
            }            
            $href = $i == 0 ? $path : $path."#".$k;            
            echo "\n<li><a href=\"$href\">$v</a></li>";
            $i++;
        }
    }
    function createGlobalNav($smap, $curr, $hideSub) {        
        foreach($smap as $k => $v) {
            $hideThis = array_key_exists("hide-this-menu-item", $v) ? TRUE : FALSE;
            if(!$hideThis) {
                $path = $v["folder"];            
                $currentPath = $smap[$curr]["folder"];                
                $hideSubMenu = array_key_exists("hide-submenu", $v) ? TRUE : $hideSub;
                $activeItem = $path == $currentPath ? TRUE : FALSE;
                if(!$hideSubMenu) {
                    $hasDropdown = "has-dropdown";
                    $dropdown = "dropdown";
                    $active = $activeItem ? "active" : "";
                    echo "\n<li class=\"$hasDropdown $active\"><a href=\"$path\">$k</a>";
                    echo "\n<ul class=\"$dropdown\">";
                    addLinks($v["submenu"], $path);
                    echo "\n</ul>";
                } else {
                    $activeClass = $activeItem ? " class=\"active\"" : "";
                    echo "\n<li$activeClass><a href=\"$path\">$k</a>";
                }            
                echo "\n</li>\n";  
            }                     
        }
    }
    init();
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wider Europe - <?php echo $current ?></title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/assets/css/foundation.css" />
    <link rel="stylesheet" href="/assets/css/widereu.css" />
    <script src="/assets/js/vendor/modernizr.js"></script>
</head>
<body>
<div class="show-for-medium-only" style="background-color:#222;padding:8px;" >
<iframe src="http://rcm-eu.amazon-adsystem.com/e/cm?t=wideeuro03-21&o=2&p=26&l=ur1&category=books&banner=1JGH33X9V9A8GTBR40R2&f=ifr" width="468" height="60" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
</div>
<div class="off-canvas-wrap">
  <div class="inner-wrap">
  <div class="f-topbar-fixed-shim show-for-large-up"></div>    
    <div class="contain-to-grid fixed show-for-large-up">
        <nav class="top-bar" data-topbar>
            <?php if($isLandingPage) { ?>
            <section class="top-bar-section">        
                <ul class="left">
                    <?php createGlobalNav($sitemap, $current, false); ?>            
                </ul>
            </section>            
            <?php } else { ?>
            <ul class="title-area">
                <li class="name"><h1><a href="/">Wider Europe</a></h1></li>
            </ul>
            <section class="top-bar-section">        
                <ul class="right">
                    <?php createGlobalNav($sitemap, $current, false); ?>            
                </ul>
            </section>
            <?php } ?>
        </nav>
    </div>

    <nav class="tab-bar hide-for-large-up">
      <section class="left-small">
        <a class="left-off-canvas-toggle menu-icon"><span></span></a>
      </section>
      <section class="middle tab-bar-section">        
        <h1 class="left">Wider Europe &gt;</h1>
        <h2 class="left"><?php echo $current ?></h2>        
      </section>
    </nav>

    <aside class="left-off-canvas-menu">
      <ul class="off-canvas-list">
        <li><a href="/">Home</a></li>
        <?php createGlobalNav($sitemap, $current, true); ?>
      </ul>
    </aside>

    <section class="main-section">
        <?php if($isLandingPage) { ?>
        <header>
            <section class="row show-for-medium-up hero-unit">
                <div class="large-12 columns">
                    <h1>Wider Europe</h1>
                </div>
            </section>               
        </header>
        <?php } ?>        
        <div class="row">            
            <div class="large-9 medium-12 columns">
                <section class="topics"> 
                    <?php printArticles($sitemap[$current]["submenu"]); ?>
                </section>
            </div>            
            <div class="large-3 columns show-for-large-up">
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

<?php if(isset($addComments) && $addComments) { ?>
    <div class="row">
        <div class="large-12 columns">
            <div id="disqus_thread"></div>
            <script type="text/javascript">
                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                var disqus_shortname = 'widereurope'; // required: replace example with your forum shortname

                /* * * DON'T EDIT BELOW THIS LINE * * */
                (function() {
                    var dsq = document.createElement('script');
                    dsq.type = 'text/javascript';
                    dsq.async = true;
                    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
            <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
        </div>
    </div>
<?php } //http://disqus.com/donsrog/ ?>

  <a class="exit-off-canvas"></a>

  <footer>
    <div class="row">
        <div class="large-12 columns">
            <p>&copy; 2014 Wider Europe Limited - contact: enquiries@widereurope.eu</p>
        </div>
    </div>  
  </footer>

  </div>
</div>
    
    <script src="/assets/js/vendor/jquery.js"></script>
    <script src="/assets/js/foundation.min.js"></script>
    <script src="/assets/js/widereu.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
