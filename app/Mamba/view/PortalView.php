<?php

namespace Mamba\view;

use Mamba\component\Button;

class PortalView extends BootstrapView
{
    private $message;
    
    protected function build ()
    {
        parent::build();
        $this->setTitle($this->getApplication()->getName());
        $this->addStyleFile($this->getBaseUrl() . "assets/font-awesome-4.0.3/css/font-awesome.css");
        $this->addStyleFile($this->getBaseUrl() . "css/style.css?_dc=4");
        $this->getBodyTag()->setAttribute("data-spy", "scroll");
        $this->getBodyTag()->setAttribute("data-target", "#mainNavbar");
        $this->getBodyTag()->add('
            <div id="messageBox" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">' . $this->getApplication()->getName() . '</h4>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>
        ');
        $this->buildHead();
        $this->buildBody();
    }
    
    protected function buildHead ()
    {
    }
    
    protected function buildBody ()
    {
        $this->bodyTag->add($this->createHeader());
        $this->bodyTag->add($this->createContent());
        $this->bodyTag->add($this->createFooter());
    }
    
    protected function createHeader ()
    {   
        return '
        <div id="mainNavbar" class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="#" class="navbar-brand">' . $this->getApplication()->getName() . '</a>
                </div>
                <div class="navbar-collapse collapse">
                    ' . $this->createHeaderContent() . '
                </div>
            </div>
        </div>';
    }
    
    protected function createContent ()
    {
        return  
        $this->createCarousel() .
        '<div class="container">
            ' . $this->createFeaturettesSection() . '
            ' . $this->createFeaturesSection() . '
        </div>';
    }
    
    protected function createFooter ()
    {
        return '<div id="footer">' . $this->createFooterContent() . '</div>';
    }
    
    public function setMessage ($message)
    {
        $this->message = $message;
    }
    
    protected function createFooterContent ()
    {
        return '
        <hr>
        <div class="container">
        <p class="pull-right"><a href="#">Volver al inicio</a></p>
        <p class="text-muted credit">© Copyright 2014. ' . $this->getApplication()->getName() . ' - Todos los derechos reservados</p>
        </div>';
    }
    
    protected function createHeaderContent ()
    {   
        return $this->createLoginForm();
    }
    
    protected function createLoginForm ()
    {
        $this->addScript('
            function showMessage (message)
            {
                $("#messageBox .modal-body").html(message);
                $("#messageBox").modal("show");
            }

            function disableLoginControls ()
            {
                $(\'input[name=username]\').prop("disabled", true);
                $(\'input[name=password]\').prop("disabled", true);
                $(\'button[name=loginbutton]\').prop("disabled", true);
                $("body").css("cursor", "progress");
            }
            
            function enableLoginControls ()
            {
                $(\'input[name=username]\').prop("disabled", false);
                $(\'input[name=password]\').prop("disabled", false);
                $(\'button[name=loginbutton]\').prop("disabled", false);
                $("body").css("cursor", "default");
            }

            function login ()
            {
                disableLoginControls();
                var username = $(\'input[name=username]\')[0].value;
                var password = $(\'input[name=password]\')[0].value;
                $.ajax("' . $this->getUrl("session/") . '?username=" + username + "&password=" + password + "&returnFormat=json",
                {
                    success: function (data)
                    {
                        if (data.success)
                        {
                            window.open("' . $this->getUrl("site/main/") . '", "_self");
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
        ');
        $loginAttributes = array("name"=>"loginbutton", "class"=>"btn btn-primary");
        $loginAttributes["onclick"] = 'login(); return false;';
        $loginButton = new Button("Ingresar", $loginAttributes);
        return '
        <form class="navbar-form navbar-right">
            <div class="form-group">
                <input type="text" name="username" placeholder="Usuario" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Contraseña" class="form-control">
            </div>
            ' . $loginButton . '
        </form>';
    }
    
    protected function createCarousel ()
    {
        return '
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
              <li data-target="#myCarousel" data-slide-to="1"></li>
              <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
              <div class="item active">
                <img src="' . $this->getBaseUrl() . 'images/slides/slide14.jpg" alt="">
                <div class="carousel-caption">
                  <h1 style="color:#ffffff">' . $this->getApplication()->getName() . '</h1>
                  <p>Nos especializamos en el desarrollo de sistemas electrónicos para la automatización y control de procesos industriales, monitoreo a distancia y telemetría.</p>
                </div>
              </div>
              <div class="item">
                <img src="' . $this->getBaseUrl() . 'images/slides/slide11.jpg" alt="">
                <div class="carousel-caption">
                  <h1 style="color:#ffffff">' . $this->getApplication()->getName() . '</h1>
                  <p>Estamos capacitados para proveer soluciones integrales con el objetivo de aumentar la productividad y rentabilidad de industrias y empresas.</p>
                </div>
              </div>
              <div class="item">
                <img src="' . $this->getBaseUrl() . 'images/slides/slide7.jpg" alt="">
                <div class="carousel-caption">
                  <h1 style="color:#ffffff">' . $this->getApplication()->getName() . '</h1>
                  <p>' . $this->getApplication()->getName() . ' esta formado por un grupo de Jóvenes Ingenieros especializados en las áreas de Electrónica, Computación y Sistemas. Nuestra sólida formación profesional y experiencia en desarrollos de sistemas integrales son la base para encontrar soluciones a su medida.</p>
                </div>
              </div>
            </div>
            <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
        </div>';
    }
    
    protected function createFeaturettesSection ()
    {
        $imageWidth = 260;
        $imageHeight = 175;
        return '
        <div class="row text-center">
            <div class="col-sm-4">
                <img class="img-thumbnail" src="' . $this->getBaseUrl() . 'images/featurettes/featurette1.jpg" data-src="holder.js/' . $imageWidth . 'x' . $imageHeight . '" alt="" style="width: ' . $imageWidth . 'px; height: ' . $imageHeight . 'px;">
                <h2>Telemetría</h2>
                <ul class="text-left">
                    <li>Información precisa y confiable en tiempo real de los parámetros de computadora de abordo de motores (ecu).</li>
                    <li>Optimización de procesos mediante mediciones remotas de variables críticas.</li>
                    <li>Reducción de costos de mantenimiento a través de sistemas predictivos de fallas.</li>
                    <li>Supervisión de estaciones meteorológicas.</li>
                </ul>
            </div>
            <div class="col-sm-4">
                <img class="img-thumbnail" src="' . $this->getBaseUrl() . 'images/featurettes/featurette2.jpg" data-src="holder.js/' . $imageWidth . 'x' . $imageHeight . '" alt="" style="width: ' . $imageWidth . 'px; height: ' . $imageHeight . 'px;">
                <h2>Servicios</h2>
                <ul class="text-left">
                    <li>Seguimiento de cargas portátiles.</li>
                    <li>Gestión de flotas.</li>
                    <li>Medición confiable de niveles en tanques, para la cuantificación de la producción.</li>
                    <li>Control de consumo de motores y elaboración del balances diarios.</li>
                    <li>Seguimiento de temperatura en cadenas de frío.</li>
                <ul>
            </div>
            <div class="col-sm-4">
                <img class="img-thumbnail" src="' . $this->getBaseUrl() . 'images/featurettes/featurette3.jpg" data-src="holder.js/' . $imageWidth . 'x' . $imageHeight . '" alt="" style="width: ' . $imageWidth . 'px; height: ' . $imageHeight . 'px;">
                <h2>Beneficios</h2>
                <ul class="text-left">
                    <li>Sistema de control y gestión de distribución.</li>
                    <li>Notificación del estado de operación de equipos.</li>
                    <li>Sistema de control y gestión de tiempos de carga y descarga.</li>
                    <li>Supervisión de entrega de mercaderías.</li>
                    <li>Análisis estadísticos de frecuencia de uso y consumos de maquinarias industriales.</li>
                </ul>
            </div>
        </div>';
    }
    
    protected function createFeaturesSection ()
    {
        return '
        <hr class="featurette-divider">
        <div class="row featurette">
            <div class="col-md-6">
                <ul class="text-left lead">
                    <li>Desarrollo de software a la medida de los requerimientos de nuestros clientes. </li>
                    <li>Instalación de equipos que técnicamente se ajustan al proyecto en particular. </li>
                    <li>Generación de herramientas para el control de la información, las cuales facilitan la monitorización automática y el registro de las mediciones. </li>
                    <li>Envío de alertas y alarmas a centros de control, garantizando operaciones seguras y eficientes</li>
                </ul>
            </div>
            <div class="col-md-6">
               <img class="featurette-image img-responsive img-thumbnail" src="' . $this->getBaseUrl() . 'images/features/feature4.jpg" data-src="holder.js/500x500/auto" alt="">
            </div>
        </div>
        <hr class="featurette-divider">
        <div class="row featurette">
            <div class="col-md-5">
                <img class="featurette-image img-responsive img-thumbnail" src="' . $this->getBaseUrl() . 'images/features/feature2.jpg" data-src="holder.js/500x500/auto" alt="">
            </div>
            <div class="col-md-7">
                <ul class="text-left lead">
                    <li>Rastreo de la ubicación de vehículos, camiones de transporte y contenedores en cualquier lugar, en cualquier momento.</li> 
                    <li>Incluso en zonas sin cobertura de señal celular. </li>
                    <li>Supervisión de las puertas de los contenedores para detectar robos y asegurarse de que la carga se mantenga segura desde su origen hasta su destino. </li>
                    <li>Envío y recepción de mensajes de texto al personal en campo.</li>
                </ul>
            </div>
        </div>
        ';
    }
}

?>
