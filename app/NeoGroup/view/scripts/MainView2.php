<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <title>NeoGroup</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->getBaseUrl(); ?>assets/bootstrap-3.1.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->getBaseUrl(); ?>assets/font-awesome-4.1.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->getBaseUrl(); ?>css/main.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->getBaseUrl(); ?>css/skin_google.css">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    </head>
    
    <body class="smart-style-3 fixed-header fixed-navigation">
        <header id="header">
            <div id="logo-group"></div>
            <div class="pull-right">
                
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="login.html" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <div id="search-mobile" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
                </div>
                <form action="#ajax/search.html" class="header-search pull-right">
                    <input id="search-fld" type="text" name="param" placeholder="Buscar ...">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
                </form>
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
                </div>
            </div>
        </header>
        
        <aside id="left-panel">
            <div class="login-info">
                <span>
                    <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut"><span>john.doe</span></a> 
                </span>
            </div>
            <nav>
                <ul>
                    <li class="">
                        <a href="ajax/dashboard.html" title="Dashboard">
                            <i class="fa fa-lg fa-fw fa-home"></i> 
                            <span class="menu-item-parent">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="ajax/inbox.html"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Inbox</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Graphs</span></a>
                        <ul>
                            <li>
                                <a href="ajax/flot.html">Flot Chart</a>
                            </li>
                            <li>
                                <a href="ajax/morris.html">Morris Charts</a>
                            </li>
                            <li>
                                <a href="ajax/inline-charts.html">Inline Charts</a>
                            </li>
                            <li>
                                <a href="ajax/dygraphs.html">Dygraphs</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <div id="main" role="main">
            <iframe id="content" src="http://www.pepe.com"></iframe>
        </div>
    </body>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="<?php echo $this->getBaseUrl(); ?>assets/bootstrap-3.1.0/js/bootstrap.min.js"></script>
    <script src="<?php echo $this->getBaseUrl(); ?>js/main.js"></script>
</html>