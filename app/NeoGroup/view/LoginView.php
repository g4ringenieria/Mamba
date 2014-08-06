<?php

namespace NeoGroup\view;

use NeoPHP\web\html\HTMLView;

class LoginView extends HTMLView
{
    protected function build()
    {
        parent::build();
        $this->addMeta(array("http-equiv"=>"Content-Type", "content"=>"text/html; charset=UTF-8"));
        $this->addMeta(array("charset"=>"utf-8"));
        $this->addMeta(array("name"=>"viewport", "content"=>"width=device-width, initial-scale=1.0"));
        $this->addScriptFile($this->getBaseUrl() . "js/jquery.min.js");
        $this->addScriptFile($this->getBaseUrl() . "assets/bootstrap-3.2.0/js/bootstrap.min.js");
        $this->addStyleFile($this->getBaseUrl() . "css/bootstrap.cerulean.min.css");
        $this->addStyleFile($this->getBaseUrl() . "css/login.css");
        $this->bodyTag->add($this->createLoginForm());
        $this->addScript('
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
    }
    
    protected function createLoginForm ()
    {
        return '
        <form role="form">
            <div class="modal show">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">' . $this->getApplication()->getName() . '</h4>
                        </div>
                        <div class="modal-body">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Nombre de usuario" name="username" type="text" autofocus="autofocus">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Contraseña" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label><input name="remember" type="checkbox" value="Remember Me"> Recordarme</label>
                                </div>
                            </fieldset>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" onclick="login(); return false;" value="Iniciar sesión"></input>
                        </div>
                    </div>
                </div>
            </div>
        </form>';
    }
}

?>