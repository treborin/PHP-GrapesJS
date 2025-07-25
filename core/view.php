<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author : PePiuoX
//  Email  : contact@pepiuox.net
//

require_once "config/loader.php";
$_SESSION["URL"] = URL;

$pages = new Routers();
$visitor = new GetVisitor();

$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") || $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
$page = $_SERVER["PHP_SELF"];
$_SESSION["language"] = "";
$initweb = $protocol . $_SERVER["HTTP_HOST"] . "/";
$pg404 = $initweb . "404.php";
$url = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$escaped_url = htmlspecialchars($url, ENT_QUOTES, "UTF-8");
$url_path = parse_url($escaped_url, PHP_URL_PATH);
$basename = pathinfo($url_path, PATHINFO_BASENAME);
$extpath = pathinfo($url, PATHINFO_EXTENSION);
$menu = '';
$title = '';
$pages->routePages();
if ($pages->GoPage() === true) {
    if ($initweb === $url) {
        $rpx = $pages->InitPage();
    } elseif (isset($basename) && !empty($basename)) {
        $npg = $pages->Pages($basename);
        if ($npg == $url) {
            $rpx = $pages->PageDataWeb($basename);
        } else {
            header("Location: $npg");
            die();
        }
    } else {
        $pages->routePages();
    }

    $bid = $rpx["id"];
    $syspath = $rpx['system_path'];
    $viewpg = $rpx['view_page'];
    $title = $rpx["title"];
    $plink = $rpx["link"];
    $purl = $rpx["url"];
    $keyword = $rpx["keyword"];
    $classification = $rpx["classification"];
    $description = $rpx["description"];
    $typepage = $rpx["type"];
    $menu = $rpx["menu"];
    $pfile = $rpx["path_file"];
    $content = $rpx["content"];
    $style = $rpx["style"];
    $lng = $rpx["language"];

    $visitor->pageViews($title);

    $language = $_SESSION["language"] = $lng;
    $request = $_SERVER["REQUEST_URI"];

    if ($viewpg === "public") {

        require_once "elements/top.php";

        if ($typepage === 'Design') {
            echo '<style>' . "\n";
            echo decodeContent($style) . "\n";
            echo '</style>' . "\n";
        }
?>
                </head>
                <body>
                <div id="wrapper">
                <div class='container-fluid min-h-screen' id="content-page">
        <?php
        require_once "elements/menu.php";
        if ($typepage === 'File') {
            include "elements/alerts.php";

            if ($request === $purl) {
                require_once $pfile . ".php";
            }
        } else if ($typepage === 'Design') {
            $string = decodeContent($content);
            if (!empty($content)) {
                $string = str_replace("<body>", "", $string);
                $string = str_replace("</body>", "", $string);
            }
            echo $string . "\n";
        }
        require_once "elements/footer.php";
        ?>
        </div>
        </div>
        </body>
        </html>
        <?php
    }
} else {
    $tempURL = explode('/', $_SERVER['REQUEST_URI']);
    $tempBASE = $tempURL[1];
    $tempURI = $tempURL[2];

    if ($tempBASE === "signin") {
        require_once "elements/top.php";
        ?>
        </head>
        <div id="wrapper">
            <div class='container-fluid min-h-screen' id="content-page">
        <?php
        require_once "elements/menu.php";
        if ($tempURI === "login") {
            require_once "pages/login/" . $tempURI . ".php";
        } else if ($tempURI === "register") {
            require_once "pages/register/" . $tempURI . ".php";
        } else if ($tempURI === "forgot-username") {
            require_once "pages/forgot/" . $tempURI . ".php";
        } else if ($tempURI === "forgot-password") {
            require_once "pages/forgot/" . $tempURI . ".php";
        } else if ($tempURI === "forgot-email") {
            require_once "pages/forgot/" . $tempURI . ".php";
        } else if ($tempURI === "forgot-pin") {
            require_once "pages/forgot/" . $tempURI . ".php";
        }
        require_once 'elements/footer.php';
        ?>
        </div>
        </div>
        </body>
        </html>
        <?php
    } else if ($tempBASE === "admin") {

        if ($tempURI === "dashboard") {
            include 'elements/header_dashboard.php';
        ?>
                 </head>
                 <body class="hold-transition sidebar-mini">
                 <div class="wrapper">  
            <?php
            if (!empty($tempURL[3])) {
                define('CMS', $tempURL[3]);
            }
            if (!empty($tempURL[4])) {
                
                if (is_numeric($tempURL[4])=== TRUE) {
                    define('IDP', $tempURL[4]);
                } else {
                    define('WS', $tempURL[4]);
                }
            }
            if (!empty($tempURL[5])) {
                if (is_numeric($tempURL[5])=== TRUE) {
                    define('IDP', $tempURL[5]);
                } else {
                    define('TBL', $tempURL[5]);
                }              
            }
            if (!empty($tempURL[6])) {
                define('IDP', $tempURL[6]);
            }
            require_once "managers/" . $tempURI . ".php";
            ?>
                 </div>
                 </body>
                 </html>
            <?php
        }

        if ($tempURI === "builder") {
            include 'elements/top_build.php';
            ?>   
                  </head>
               <body id="builder">  
            <?php
            if (!empty($tempURL[3])) {
                define('PAG', $tempURL[3]);
            }
            if (!empty($tempURL[4])) {
                define('IDP', $tempURL[4]);
            }
            require_once "managers/" . $tempURI . ".php";
            ?>
                 
                 </body>
                 </html>
            <?php
        }
            ?>
        <?php
    } else if ($tempBASE === "profile") {
        include 'elements/header.php';
        ?>
                </head>
        <body class="hold-transition sidebar-mini">
        <div class="wrapper">  
        <?php
        if (!empty($tempURL[3])) {
            define('USR', $tempURL[3]);
        }
        if (!empty($tempURL[4])) {
            define('WS', $tempURL[4]);
        }
        require_once "users/" . $tempURI . ".php";
        ?>
         </div>
         </body>
         </html>
        <?php
    } else {
        header("Location " . $pg404);
        die();
    }
        ?>
          
    <?php
}
    ?>
