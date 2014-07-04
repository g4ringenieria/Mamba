<!DOCTYPE html>
<html lang="en-us">	
    <head>
        <meta charset="utf-8">
        <title>SmartAdmin (AJAX)</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- #CSS Links -->
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->getBaseUrl(); ?>assets/bootstrap-3.1.0/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->getBaseUrl(); ?>assets/font-awesome-4.1.0/css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->getBaseUrl(); ?>css/smartadmin-production.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->getBaseUrl(); ?>css/smartadmin-skins.min.css">

        <!-- SmartAdmin RTL Support is under construction
                 This RTL CSS will be released in version 1.5
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.min.css"> -->

        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.
        <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->getBaseUrl(); ?>css/demo.min.css">

        <!-- #FAVICONS -->
        <link rel="shortcut icon" href="<?php echo $this->getBaseUrl(); ?>images/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo $this->getBaseUrl(); ?>images/favicon/favicon.ico" type="image/x-icon">

        <!-- #GOOGLE FONT -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

        <!-- #APP SCREEN / ICONS -->
        <link rel="apple-touch-icon" href="<?php echo $this->getBaseUrl(); ?>images/splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->getBaseUrl(); ?>images/splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->getBaseUrl(); ?>images/splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->getBaseUrl(); ?>images/splash/touch-icon-ipad-retina.png">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="<?php echo $this->getBaseUrl(); ?>images/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="<?php echo $this->getBaseUrl(); ?>images/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="<?php echo $this->getBaseUrl(); ?>images/splash/iphone.png" media="screen and (max-device-width: 320px)">
    </head>
    
    <body class="smart-style-3">
        <!-- #HEADER -->
        <header id="header">
            <div id="logo-group">
                <!-- PLACE YOUR LOGO HERE -->
<!--                <span id="logo"> <img src="img/logo.png" alt="SmartAdmin"> </span>-->
                <!-- END LOGO PLACEHOLDER -->
            </div>
            <div class="pull-right">

                <!-- collapse menu button -->
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <!-- end collapse menu -->

                <!-- #MOBILE -->
                <!-- Top menu profile link : this shows only when top menu is active -->
                <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
                    <li class="">
                        <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
                            <img src="img/avatars/sunny.png" alt="John Doe" class="online" />  
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#ajax/profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="login.html" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <!-- logout button -->
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="login.html" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <!-- end logout button -->

                <!-- search mobile button (this is hidden till mobile view port) -->
                <div id="search-mobile" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
                </div>
                <!-- end search mobile button -->

                <!-- #SEARCH -->
                <!-- input: search field -->
                <form action="#ajax/search.html" class="header-search pull-right">
                    <input id="search-fld" type="text" name="param" placeholder="Buscar ...">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
                </form>
                <!-- end input: search field -->

                <!-- fullscreen button -->
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
                </div>
                <!-- end fullscreen button -->
            </div>
            <!-- end pulled right: nav area -->

        </header>
        <!-- END HEADER -->

        <!-- #NAVIGATION -->
        <!-- Left panel : Navigation area -->
        <!-- Note: This width of the aside area can be adjusted through LESS variables -->
        <aside id="left-panel">

            <!-- User info -->
            <div class="login-info">
                <span>  User image size is adjusted inside CSS, it should stay as is  

                    <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
<!--                        <img src="img/avatars/sunny.png" alt="me" class="online" /> -->
                        <span>
                            john.doe 
                        </span>
                        <i class="fa fa-angle-down"></i>
                    </a> 

                </span>
            </div>
            <!-- end user info -->
            
            <nav>
                <ul>
                    <li class="">
                        <a href="ajax/dashboard.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="ajax/inbox.html"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Inbox</span><span class="badge pull-right inbox-badge">14</span></a>
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
                                <a href="ajax/dygraphs.html">Dygraphs <span class="badge pull-right inbox-badge bg-color-yellow">new</span></a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-table"></i> <span class="menu-item-parent">Tables</span></a>
                        <ul>
                            <li>
                                <a href="ajax/table.html">Normal Tables</a>
                            </li>
                            <li>
                                <a href="ajax/datatables.html">Data Tables <span class="badge inbox-badge bg-color-greenLight">v1.10</span></a>
                            </li>
                            <li>
                                <a href="ajax/jqgrid.html">Jquery Grid</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Forms</span></a>
                        <ul>
                            <li>
                                <a href="ajax/form-elements.html">Smart Form Elements</a>
                            </li>
                            <li>
                                <a href="ajax/form-templates.html">Smart Form Layouts</a>
                            </li>
                            <li>
                                <a href="ajax/validation.html">Smart Form Validation</a>
                            </li>
                            <li>
                                <a href="ajax/bootstrap-forms.html">Bootstrap Form Elements</a>
                            </li>
                            <li>
                                <a href="ajax/plugins.html">Form Plugins</a>
                            </li>
                            <li>
                                <a href="ajax/wizard.html">Wizards</a>
                            </li>
                            <li>
                                <a href="ajax/other-editors.html">Bootstrap Editors</a>
                            </li>
                            <li>
                                <a href="ajax/dropzone.html">Dropzone</a>
                            </li>
                            <li>
                                <a href="ajax/image-editor.html">Image Cropping <span class="badge pull-right inbox-badge bg-color-yellow">new</span></a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-desktop"></i> <span class="menu-item-parent">UI Elements</span></a>
                        <ul>
                            <li>
                                <a href="ajax/general-elements.html">General Elements</a>
                            </li>
                            <li>
                                <a href="ajax/buttons.html">Buttons</a>
                            </li>
                            <li>
                                <a href="#">Icons</a>
                                <ul>
                                    <li>
                                        <a href="ajax/fa.html"><i class="fa fa-plane"></i> Font Awesome</a>
                                    </li>
                                    <li>
                                        <a href="ajax/glyph.html"><i class="glyphicon glyphicon-plane"></i> Glyph Icons</a>
                                    </li>
                                    <li>
                                        <a href="ajax/flags.html"><i class="fa fa-flag"></i> Flags</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="ajax/grid.html">Grid</a>
                            </li>
                            <li>
                                <a href="ajax/treeview.html">Tree View</a>
                            </li>
                            <li>
                                <a href="ajax/nestable-list.html">Nestable Lists</a>
                            </li>
                            <li>
                                <a href="ajax/jqui.html">JQuery UI</a>
                            </li>
                            <li>
                                <a href="ajax/typography.html">Typography</a>
                            </li>
                            <li>
                                <a href="#">Six Level Menu</a>
                                <ul>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-folder-open"></i> Item #2</a>
                                        <ul>
                                            <li>
                                                <a href="#"><i class="fa fa-fw fa-folder-open"></i> Sub #2.1 </a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> Item #2.1.1</a>
                                                    </li>
                                                    <li>
                                                        <a href="#"><i class="fa fa-fw fa-plus"></i> Expand</a>
                                                        <ul>
                                                            <li>
                                                                <a href="#"><i class="fa fa-fw fa-file-text"></i> File</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fa fa-fw fa-trash-o"></i> Delete</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-folder-open"></i> Item #3</a>

                                        <ul>
                                            <li>
                                                <a href="#"><i class="fa fa-fw fa-folder-open"></i> 3ed Level </a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> File</a>
                                                    </li>
                                                    <li>
                                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> File</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>

                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="ajax/calendar.html"><i class="fa fa-lg fa-fw fa-calendar"><em>3</em></i> <span class="menu-item-parent">Calendar</span></a>
                    </li>
                    <li>
                        <a href="ajax/widgets.html"><i class="fa fa-lg fa-fw fa-list-alt"></i> <span class="menu-item-parent">Widgets</span></a>
                    </li>
                    <li>
                        <a href="ajax/gallery.html"><i class="fa fa-lg fa-fw fa-picture-o"></i> <span class="menu-item-parent">Gallery</span></a>
                    </li>
                    <li>
                        <a href="ajax/gmap-xml.html"><i class="fa fa-lg fa-fw fa-map-marker"></i> <span class="menu-item-parent">GMap Skins</span><span class="badge bg-color-greenLight pull-right inbox-badge">9</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-windows"></i> <span class="menu-item-parent">Miscellaneous</span></a>
                        <ul>
                            <li>
                                <a href="#"><i class="fa fa-file"></i> Other Pages</a>
                                <ul>
                                    <li>
                                        <a href="ajax/forum.html">Forum Layout</a>
                                    </li>
                                    <li>
                                        <a href="ajax/profile.html">Profile</a>
                                    </li>
                                    <li>
                                        <a href="ajax/timeline.html">Timeline</a>
                                    </li>
                                </ul>	
                            </li>
                            <li>
                                <a href="ajax/pricing-table.html">Pricing Tables</a>
                            </li>
                            <li>
                                <a href="ajax/invoice.html">Invoice</a>
                            </li>
                            <li>
                                <a href="login.html" target="_top">Login</a>
                            </li>
                            <li>
                                <a href="register.html" target="_top">Register</a>
                            </li>
                            <li>
                                <a href="lock.html" target="_top">Locked Screen</a>
                            </li>
                            <li>
                                <a href="ajax/error404.html">Error 404</a>
                            </li>
                            <li>
                                <a href="ajax/error500.html">Error 500</a>
                            </li>
                            <li>
                                <a href="ajax/blank_.html">Blank Page</a>
                            </li>
                            <li>
                                <a href="ajax/email-template.html">Email Template</a>
                            </li>
                            <li>
                                <a href="ajax/search.html">Search Page</a>
                            </li>
                            <li>
                                <a href="ajax/ckeditor.html">CK Editor</a>
                            </li>
                        </ul>
                    </li>
                    <li class="top-menu-hidden">
                        <a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">SmartAdmin Intel</span></a>
                        <ul>
                            <li>
                                <a href="ajax/difver.html"><i class="fa fa-stack-overflow"></i> Different Versions</a>
                            </li>
                            <li>
                                <a href="ajax/applayout.html"><i class="fa fa-cube"></i> App Settings</a>
                            </li>
                            <li>
                                <a href="http://bootstraphunter.com/smartadmin/BUGTRACK/track_/documentation/index.html" target="_blank"><i class="fa fa-book"></i> Documentation</a>
                            </li>
                            <li>
                                <a href="http://bootstraphunter.com/smartadmin/BUGTRACK/track_/" target="_blank"><i class="fa fa-bug"></i> Bug Tracker</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>

        </aside>
        <!-- END NAVIGATION -->

        <!-- #MAIN PANEL -->
        <div id="main" role="main">

            <!-- RIBBON -->
            <div id="ribbon">

                <span class="ribbon-button-alignment"> 
                    <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true" data-reset-msg="Would you like to RESET all your saved widgets and clear LocalStorage?"><i class="fa fa-refresh"></i></span> 
                </span>

                <!-- breadcrumb -->
                <ol class="breadcrumb">
                    <!-- This is auto generated -->
                </ol>
                <!-- end breadcrumb -->

                <!-- You can also add more buttons to the
                ribbon for further usability

                Example below:

                <span class="ribbon-button-alignment pull-right" style="margin-right:25px">
                        <span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa fa-grid"></i> Change Grid</span>
                        <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa fa-plus"></i> Add</span>
                        <span id="search" class="btn btn-ribbon" data-title="search"><i class="fa fa-search"></i> <span class="hidden-mobile">Search</span></span>
                </span> -->

            </div>
            <!-- END RIBBON -->

            <!-- #MAIN CONTENT -->
            <div id="content">

            </div>

            <!-- END #MAIN CONTENT -->

        </div>
        <!-- END #MAIN PANEL -->
        
        <!-- #PLUGINS -->
        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script>
            if (!window.jQuery) {
                document.write('<script src="<?php echo $this->getBaseUrl(); ?>js/libs/jquery-2.0.2.min.js"><\/script>');
            }
        </script>

        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="<?php echo $this->getBaseUrl(); ?>js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>

        <!-- IMPORTANT: APP CONFIG -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/app.config.js"></script>

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
        <script src="<?php echo $this->getBaseUrl(); ?>js/jquery.ui.touch-punch.min.js"></script> 

        <!-- BOOTSTRAP JS -->
        <script src="<?php echo $this->getBaseUrl(); ?>assets/bootstrap-3.1.0/js/bootstrap.min.js"></script>

        <!-- CUSTOM NOTIFICATION -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/SmartNotification.min.js"></script>

        <!-- JARVIS WIDGETS -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/jarvis.widget.min.js"></script>

        <!-- EASY PIE CHARTS -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/jquery.easy-pie-chart.min.js"></script>

        <!-- SPARKLINES -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/jquery.sparkline.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/jquery.validate.min.js"></script>

        <!-- JQUERY MASKED INPUT -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/jquery.maskedinput.min.js"></script>

        <!-- JQUERY SELECT2 INPUT -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/select2.min.js"></script>

        <!-- JQUERY UI + Bootstrap Slider -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/bootstrap-slider.min.js"></script>

        <!-- browser msie issue fix -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/jquery.mb.browser.min.js"></script>

        <!-- FastClick: For mobile devices: you can disable this in app.js -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/fastclick.min.js"></script>

        <!--[if IE 8]>
                <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
        <![endif]-->

        <!-- Demo purpose only -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/demo.min.js"></script>

        <!-- MAIN APP JS FILE -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/app.min.js"></script>

        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
        <!-- Voice command : plugin -->
        <script src="<?php echo $this->getBaseUrl(); ?>js/voicecommand.min.js"></script>

        <!-- Your GOOGLE ANALYTICS CODE Below -->
        <script type="text/javascript">
		
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-43548732-3']);
            _gaq.push(['_trackPageview']);
		
            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
		
        </script>

    </body>
</html>