<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="ico/favicon.ico">

        <title>neoGroup</title>

        <!-- Bootstrap core CSS -->
        <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->

        <!-- Custom styles for this template -->
        <link href="<?php echo $this->getBaseUrl(); ?>css/paperclip.css" rel="stylesheet">

        <!-- Resources -->
        <link href="<?php echo $this->getBaseUrl(); ?>assets/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo $this->getBaseUrl(); ?>css/animate.css" rel="stylesheet">
        <link href="<?php echo $this->getBaseUrl(); ?>css/lightbox.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

        <div id="messageBox" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">NeoGroup</h4>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo $this->getBaseUrl(); ?>index.php"><img src="<?php echo $this->getBaseUrl(); ?>images/portal/logo.png" alt="..."></a>
                </div>   
                <div class="navbar-collapse collapse">
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Usuario" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Contraseña" class="form-control">
                        </div>
                        <button class="btn btn-red" name="loginbutton" onclick="login(); return false;">Ingresar</button>
                    </form>
                </div>
            </div>
        </div> <!-- / .navigation -->

        <!-- Wrapper -->
        <div class="wrapper">

            <!-- Home Slider -->
            <div class="home-slider">
                <!-- Carousel -->
                <div id="home-slider" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#home-slider" data-slide-to="0" class="active"></li>
                        <li data-target="#home-slider" data-slide-to="1"></li>
                        <li data-target="#home-slider" data-slide-to="2"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <!-- Slide #1 -->
                        <div class="item active" id="item-1">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h1 class="first-child animated slideInDown delay-2">Powerful Responsive Theme For Business and Personal Projects</h1>
                                        <h3 class="animated slideInDown delay-3">Beautiful Theme That Works Out Of The Box</h3>
                                        <p class="text-muted animated slideInLeft delay-4">Nulla pretium libero interdum, tempus lorem non, rutrum diam. Quisque pellentesque diam sed pulvinar lobortis. <a href="#" id="tooltip" data-toggle="tooltip" data-placement="top" title="Quisque pellentesque diam.">Vestibulum ante ipsum</a> primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
                                        <a href="#" class="btn btn-lg btn-red animated fadeInUpBig delay-5">Purchase Now</a>
                                    </div>
                                    <div class="col-sm-6 hidden-xs">
                                        <img class="img-responsive" src="<?php echo $this->getBaseUrl(); ?>images/portal/showcase.png" alt="...">
                                    </div>
                                </div> <!-- / .row -->
                            </div> <!-- / .container -->
                        </div> <!-- / .item -->
                        <!-- Slide #2 -->
                        <div class="item" id="item-2">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <ul class="lead">
                                            <li class="animated slideInLeft delay-2"><span>Built with Bootstrap 3x</span></li>
                                            <li class="animated slideInLeft delay-3"><span>20+ HTML Templates</span></li>
                                            <li class="animated slideInLeft delay-4"><span>Isotope Gallery</span></li>
                                            <li class="animated slideInLeft delay-5"><span>LESS Files Included</span></li>
                                        </ul>
                                        <ul class="lead string">
                                            <li class="animated fadeInUpBig delay-6"><span>Professional Multi-purpose Theme</span></li>
                                            <li class="animated fadeInUpBig delay-7"><span>That Works Out Of The Box</span></li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6 hidden-xs">
                                        <img class="img-responsive" src="<?php echo $this->getBaseUrl(); ?>images/portal/macbook.png" alt="...">
                                    </div>
                                </div> <!-- / .row -->
                            </div> <!-- / .container -->          
                        </div> <!-- / .item -->
                        <!-- Slide #3 -->
                        <div class="item" id="item-3">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h1 class="first-child animated slideInDown delay-2">Control de temperaturas</h1>
                                        <ul>
                                            <li class="animated slideInLeft delay-4"><i class="fa fa-chevron-circle-right fa-fw"></i> Control a distancia (internet, mail, móvil, etc)</li>
                                            <li class="animated slideInLeft delay-5"><i class="fa fa-chevron-circle-right fa-fw"></i> Centralización de todos los datos de varios equipos y recintos</li>
                                            <li class="animated slideInLeft delay-6"><i class="fa fa-chevron-circle-right fa-fw"></i> Almacenamiento y registro de datos históricos y con gran periodicidad</li>
                                            <li class="animated slideInLeft delay-7"><i class="fa fa-chevron-circle-right fa-fw"></i> Configuración de alarmas por subidas de temperaturas</li>
                                            <li class="animated slideInLeft delay-8"><i class="fa fa-chevron-circle-right fa-fw"></i> Descarga de informes y gráficas</li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6 hidden-xs">
                                        <img class="img-responsive" src="<?php echo $this->getBaseUrl(); ?>images/portal/iphone.png" alt="...">
                                    </div>
                                </div> <!-- / .row -->
                            </div> <!-- / .container -->            
                        </div> <!-- / .item -->
                    </div> <!-- / .carousel -->
                    <!-- Controls -->
                    <a class="carousel-arrow carousel-arrow-prev" href="#home-slider" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <a class="carousel-arrow carousel-arrow-next" href="#home-slider" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div> <!-- / .home-slider -->

            <!-- Services -->
            <div class="home-services">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-7">
                            <ul>
                                <li>
                                    <i class="fa fa-gears"></i>
                                    <p><span>Built with </span> Bootstrap 3</p>
                                </li>
                                <li>
                                    <i class="fa fa-flag"></i>
                                    <p>Font Awesome <span>Icons</span></p>
                                </li>
                                <li>
                                    <i class="fa fa-picture-o"></i>
                                    <p>Isotope <span>Portfolio</span></p>
                                </li>
                                <li>
                                    <i class="fa fa-envelope-o"></i>
                                    <p>24/7 Support <span>by e-mail</span></p>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-5 hidden-sm hidden-xs">
                            <p class="lead">Ready-to-go Solution Built with Bootstrap 3</p>
                        </div>
                    </div> <!-- / .row -->
                </div> <!-- / .container -->
            </div> <!-- / .services -->

            <!-- Browser Showcase -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="first-child text-center">Paperclip: Multi-purpose Professional Theme</h2>
                        <h4 class="text-blue text-center">Perfect for corporate websites and mobile apps.</h4>
                        <div class="browser-showcase">
                            <img src="<?php echo $this->getBaseUrl(); ?>images/portal/browsers.png" class="img-responsive" alt="...">
                        </div>
                    </div>
                </div> <!-- / .row -->
            </div> <!-- / .container -->

            <!-- Main Services -->
            <div class="main-services">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="services">
                                <div class="service-item">
                                    <i class="fa fa-gear"></i>
                                    <div class="service-desc">
                                        <h4>Desarrollos a medida</h4>
                                        <p>Desarrollo de software a la medida de los requerimientos de nuestros clientes.</p>
                                        <p>Instalación de equipos que técnicamente se ajustan al proyecto en particular.</p>
                                    </div>
                                </div>
                            </div> <!-- / .services -->
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="services">
                                <div class="service-item">
                                    <i class="fa fa-refresh"></i>
                                    <div class="service-desc">
                                        <h4>Reportes</h4>
                                        <p>Generación de herramientas para el control de la información, las cuales facilitan la monitorización automática y el registro de las mediciones.</p>
                                    </div>
                                </div>
                            </div> <!-- / .services -->
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="services">
                                <div class="service-item">
                                    <i class="fa fa-plus"></i>
                                    <div class="service-desc">
                                        <h4>Control</h4>
                                        <p>Rastreo de la ubicación de vehículos, camiones de transporte y contenedores en cualquier lugar, en cualquier momento, incluso en zonas sin cobertura de señal celular.</p>
                                    </div>
                                </div>
                            </div> <!-- / .services -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="services">
                                <div class="service-item">
                                    <i class="fa fa-arrows-alt"></i>
                                    <div class="service-desc">
                                        <h4>Supervisión</h4>
                                        <p>Supervisión de las puertas de los contenedores para detectar robos y asegurarse de que la carga se mantenga segura desde su origen hasta su destino.</p>
                                    </div>
                                </div>
                            </div> <!-- / .services -->
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="services">
                                <div class="service-item">
                                    <i class="fa fa-envelope"></i>
                                    <div class="service-desc">
                                        <h4>Alertas / Mensajes</h4>
                                        <p>Envío de alertas y alarmas a centros de control, garantizando operaciones seguras y eficientes. Envío y recepción de mensajes de texto al personal en campo.</p>
                                    </div>
                                </div>
                            </div> <!-- / .services -->
                        </div>
                        <div class="col-sm-4">
                            <div class="services">
                                <div class="service-item">
                                    <i class="fa fa-picture-o"></i>
                                    <div class="service-desc">
                                        <h4>Automatización</h4>
                                        <p>Generación de herramientas para el control de la información, las cuales facilitan la monitorización automática y el registro de las mediciones</p>
                                    </div>
                                </div>
                            </div> <!-- / .services -->
                        </div>
                    </div> <!-- / .row -->
                </div> <!-- / .container -->
            </div> <!-- / .main-features -->

            <!-- Responsive Showcase -->
            <div class="responsive-showcase">
                <div class="container">
                    <div class="responsive-design">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Fully Responsive Design</h2>
                                <p class="lead text-muted">
                                    Pellentesque magna turpis, porttitor non leo ac, imperdiet sollicitudin neque. Sed id condimentum quam. Ut dui velit, suscipit et libero vulputate, feugiat vestibulum velit. Integer auctor, orci sit amet tincidunt posuere.
                                </p>
                                <ul class="list-inline hidden-xs">
                                    <li><i class="fa fa-mobile text-blue inactive" data-device="#iphone"></i></li>
                                    <li><i class="fa fa-tablet text-blue inactive" data-device="#ipad"></i></li>
                                    <li><i class="fa fa-laptop text-blue" data-device="#macbook"></i></li>
                                    <li><i class="fa fa-desktop text-blue inactive" data-device="#imac"></i></li>
                                </ul>
                            </div>
                            <div class="col-sm-6 hidden-xs">
                                <img class="img-responsive show" src="<?php echo $this->getBaseUrl(); ?>images/portal/macbook.png" alt="..." id="macbook">
                                <img class="img-responsive hidden" src="<?php echo $this->getBaseUrl(); ?>images/portal/imac.png" alt="..." id="imac">
                                <img class="img-responsive hidden" src="<?php echo $this->getBaseUrl(); ?>images/portal/ipad.png" alt="..." id="ipad">
                                <img class="img-responsive hidden" src="<?php echo $this->getBaseUrl(); ?>images/portal/iphone.png" alt="..." id="iphone">
                            </div>
                        </div> <!-- / .row -->
                    </div> <!-- / .template-thumbnails -->
                </div> <!-- / .container -->
            </div> <!-- / .template-showcase -->

            <!-- Feedback -->
            <div class="feedbacks">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <h2 class="text-center">Quienes somos</h2>
                        </div>
                    </div> <!-- / .row -->
                    <div class="row">
                        
                        <div class="col-sm-6 col-md-6">
                            <div class="feedback">
                                <i class="fa fa-user fa-2x"></i>
                                <div>
                                    <p>
                                        NeoGroup esta formado por un grupo de Jóvenes Ingenieros especializados en las áreas de Electrónica, Computación y Sistemas. Nuestra sólida formación profesional y experiencia en desarrollos de sistemas integrales son la base para encontrar soluciones a su medida.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="feedback">
                                <i class="fa fa-user fa-2x"></i>
                                <div>
                                    <p>
                                        Estamos capacitados para proveer soluciones integrales con el objetivo de aumentar la productividad y rentabilidad de industrias y empresas.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-sm-4 col-md-4">
                            <div class="feedback">
                                <i class="fa fa-user fa-2x"></i>
                                <div>
                                    <p>
                                        In vitae sodales massa. Proin commodo nibh sed nisi placerat facilisis. Fusce fringilla elit ipsum, vitae viverra ligula hendrerit nec.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="feedback">
                                <i class="fa fa-user fa-2x"></i>
                                <div>
                                    <p>
                                        In vitae sodales massa. Proin commodo nibh sed nisi placerat facilisis. Fusce fringilla elit ipsum, vitae viverra ligula hendrerit nec.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <div class="feedback">
                                <i class="fa fa-user fa-2x"></i>
                                <div>
                                    <p>
                                        In vitae sodales massa. Proin commodo nibh sed nisi placerat facilisis. Fusce fringilla elit ipsum, vitae viverra ligula hendrerit nec.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- / .row -->
                </div> <!-- / .container -->
            </div> <!-- / .feedback -->

            <!-- Recent Blog Posts -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="text-center">Recent Blog Posts</h2>
                    </div>
                </div> <!-- / .row -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="blog">
                            <img src="<?php echo $this->getBaseUrl(); ?>images/portal/photo-1.jpg" alt="...">
                            <div class="blog-desc">
                                <h3>
                                    <a href="blog-post.html">Sed lacinia suscipit lacus non sodales. Pellentesque lacinia ornare justo eu tincidunt.</a>
                                </h3>
                                <hr>
                                <p class="text-muted">by John Doe</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nisi lorem, elementum sed feugiat ac, pharetra lacinia mi. Integer iaculis risus sed quam vehicula, sit amet lacinia sem rutrum. Integer ligula orci.</p>
                                <a class="btn btn-lg btn-red" href="blog-post.html">Read More...</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="blog">
                            <img src="<?php echo $this->getBaseUrl(); ?>images/portal/photo-2.jpg" alt="...">
                            <div class="blog-desc">
                                <h3>
                                    <a href="blog-post.html">Nulla pretium libero interdum, tempus lorem non, rutrum diam. Lorem ipsum dolor sit amet.</a>
                                </h3>
                                <hr>
                                <p class="text-muted">by John Doe</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nisi lorem, elementum sed feugiat ac, pharetra lacinia mi. Integer iaculis risus sed quam vehicula, sit amet lacinia sem rutrum. Integer ligula orci.</p>
                                <a class="btn btn-lg btn-red" href="blog-post.html">Read More...</a>
                            </div>
                        </div>
                    </div>
                </div> <!-- / .row -->
            </div> <!-- / .container -->

        </div> <!-- / .wrapper -->

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <!-- Contact Us -->
                    <div class="col-sm-4">
                        <h4><i class="fa fa-map-marker text-red"></i> Contact Us</h4>
                        <p>Do not hesitate to contact us if you have any questions or feature requests:</p>
                        <p>
                            San Francisco, CA 94101<br />
                            1987 Lincoln Street<br />
                            Phone: +0 000 000 00 00<br />
                            Fax: +0 000 000 00 00<br />
                            Email: <a href="#">admin@mysite.com</a>
                        </p>
                    </div>
                    <!-- Recent Tweets -->
                    <div class="col-sm-4">
                        <h4><i class="fa fa-twitter-square text-red"></i> Recent Tweets</h4>
                        <div class="tweet">
                            <i class="fa fa-twitter fa-2x"></i>
                            <p>
                                Ut tincidunt erat quis viverra consectetur. Suspendisse tempus vulputate leo.
                                <a href="#">1 day ago</a>
                            </p>
                        </div>
                        <div class="tweet">
                            <i class="fa fa-twitter fa-2x"></i>
                            <p>
                                Mauris eget lacus ut ipsum dignissim malesuada quis nec ante.
                                <a href="#">2 days ago</a>
                            </p>
                        </div>
                    </div>
                    <!-- Newsletter -->
                    <div class="col-sm-4">
                        <h4><i class="fa fa-envelope text-red"></i> Newsletter</h4>
                        <p>
                            Enter your e-mail below to subscribe to our free newsletter.
                            <br>
                            We promise not to bother you often!
                        </p>
                        <form class="form" role="form">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <label class="sr-only" for="subscribe-email">Email address</label>
                                        <input type="email" class="form-control" id="subscribe-email" placeholder="Enter your email">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-default">OK</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Copyright -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="copyright">
                        Copyright 2014 - NeoGroup | Todos los derechos reservados
                    </div>
                </div>
            </div>  <!-- / .row -->
        </div> <!-- / .container -->


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="<?php echo $this->getBaseUrl(); ?>assets/bootstrap-3.1.0/js/bootstrap.min.js"></script>
        <script src="<?php echo $this->getBaseUrl(); ?>js/scrolltopcontrol.js"></script>
        <script src="<?php echo $this->getBaseUrl(); ?>js/lightbox-2.6.min.js"></script>
        <script src="<?php echo $this->getBaseUrl(); ?>js/custom.js"></script>
        <script src="<?php echo $this->getBaseUrl(); ?>js/index.js"></script>
        <script type="text/javascript">

            function showMessage (message)
            {
                $("#messageBox .modal-body").html(message);
                $("#messageBox").modal("show");
            }

            function disableLoginControls ()
            {
                $('input[name=username]').prop("disabled", true);
                $('input[name=password]').prop("disabled", true);
                $('button[name=loginbutton]').prop("disabled", true);
                $("body").css("cursor", "progress");
            }
            
            function enableLoginControls ()
            {
                $('input[name=username]').prop("disabled", false);
                $('input[name=password]').prop("disabled", false);
                $('button[name=loginbutton]').prop("disabled", false);
                $("body").css("cursor", "default");
            }

            function login ()
            {
                disableLoginControls();
                var username = $('input[name=username]')[0].value;
                var password = $('input[name=password]')[0].value;
                $.ajax("<?php echo $this->getUrl("session/"); ?>?username=" + username + "&password=" + password + "&returnFormat=json",
                {
                    success: function (data)
                    {
                        if (data.success)
                        {
                            window.open("<?php echo $this->getUrl("site/main/"); ?>", "_self");
                        }
                        else
                        {
                            showMessage(data.errorMessage);
                            enableLoginControls();
                        }
                    },
                    error: function (qXHR, textStatus, errorThrown)
                    {
                        showMessage(textStatus + " - " + errorThrown);
                        enableLoginControls();
                    },
                    timeout: function ()
                    {
                        showMessage("Se ha agotado el tiempo de conexión. Intente más tarde");
                        enableLoginControls();
                    }
                });
            }
        </script>
    </body>
</html>