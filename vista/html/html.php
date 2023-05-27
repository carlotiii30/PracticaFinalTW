<?php
include "codigoInicial.php";
session_start();
function htmlStart($titulo, $activo = '') {
    __htmlIdiomas();
    __htmlInicio($titulo);
    __htmlEncabezado($activo);
  }
  
  function htmlEnd() {
    __htmlPiepagina();
    __htmlFin();
  }
  
  function htmlNavAdmin($activo){
    global $mensajes;
    global $idioma;
    htmlNav('menu',[['texto'=>$mensajes[$idioma]["VerIncidencias"], 'url'=>'verIncidencias.php'],
                  ['texto'=>$mensajes[$idioma]["NuevaIncidencia"], 'url'=>'nuevaIncidencia.php'],
                  ['texto'=>$mensajes[$idioma]["MisIncidencias"], 'url'=>'misIncidencias.php'],
                  ['texto'=>$mensajes[$idioma]["GestionUsuarios"], 'url'=>'gestionUsuarios.php'],
                  ['texto'=>$mensajes[$idioma]["Log"], 'url'=>'log.php'],
                  ['texto'=>$mensajes[$idioma]["GestionBBDD"], 'url'=>'gestionBD.php']],$activo);
  }

function htmlNav($clase, $menu, $activo = '')
{
  echo "<nav class='$clase'>";
  echo "<ul>";
  foreach ($menu as $elem) {
    echo "<li> <a " . ($activo == $elem['texto'] ? "class='activo' " : '') . "href='{$elem['url']}'>{$elem['texto']}</a> </li>";
  }
  echo "</ul>";
  echo '</nav>';
  __htmlContenidosIni();
}

function htmlPagInicio()
{
  global $mensajes;
  global $idioma;

  echo <<<HTML
    <section class="principal">
        <h1>
            {$mensajes[$idioma]["Bienvenida"]}
        </h1>
        <p>
            {$mensajes[$idioma]["Inicio"]}
        </p>
        <p>
            {$mensajes[$idioma]["Informacion"]}
        </p>
    </section>
    HTML;
}

function htmlAside($login){
  echo '<aside>';
  if(!isset($_SESSION['autenticado'])){
    __htmlLogin();
  }else{

  }
}
  
  




// ******** Funciones privadas de este módulo

function __htmlIdiomas()
{
  global $mensajes;
  global $idioma;

  echo <<<HTML
    <div class="elegirIdioma">
    <!-- Seleccionar idioma -->
    <img class="imgIdioma" src="./vista/imagenes/mundo_sf.png" alt="">
    <p>
        {$mensajes[$idioma]["Lenguaje"]}
    </p>
    <form method="get" action="">
        <div class="entrada">
            <select name="idioma">
    HTML;
  echo '<option value="es" ' . seleccionado("idioma", "es") . '>';
  echo <<<HTML
                    {$mensajes[$idioma]["Espanol"]}
                </option>
    HTML;
  echo '<option value="en" ' . seleccionado("idioma", "en") . '>';
  echo <<<HTML
                    {$mensajes[$idioma]["Ingles"]}
                </option>
    HTML;
  echo '<option value="fr" ' . seleccionado("idioma", "fr") . '>';
  echo <<<HTML
                    {$mensajes[$idioma]["Frances"]}
                </option>
            </select>
        </div>
        <div class="botones">
            <input type="submit" name="aplicar" value="{$mensajes[$idioma]["Aplicar"]}">
        </div>
    </form>
    </div>
    HTML;

}

// Cabecera de página web
function __htmlInicio($titulo)
{
  echo <<<HTML
  <!DOCTYPE html>
  <html>
  <head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="vista/css/estilos.css" />
  <title>{$titulo}</title>
  </head>
  <body>	
  HTML;
}

// Contenidos INICIO
function __htmlContenidosIni()
{
  echo '<main>';
}

// Encabezado
function __htmlEncabezado($activo)
{
  echo <<<HTML
  <div class='cabecera'>
    <img src="./vista/imagenes/SugQueRec.png" alt="">
    <h1>SAL Y QUÉJATE</h1>
  </div>
  HTML;
}


// Pie de página
function __htmlPiepagina()
{
  __htmlContenidosFin();
  echo <<<HTML
  <footer>
    <p>Trabajo final de Tecnologías Web. &copy; Carlota de la Vega Soriano y Manuel Vico Arboledas</p>
  </footer>
  HTML;
}

// Contenidos FIN
function __htmlContenidosFin()
{
  echo '</aside></main>';
}

// Cierre de página web
function __htmlFin()
{
  echo '</body></html>';
}

  function __htmlLogin(){
    global $mensajes;
    global $idioma;
    echo <<< HTML
        <form action="BD/procesarInicioSesion.php" method="POST">
                <div class="login">
                    <div class="entrada">
                        <label for="nombre">
                            {$mensajes[$idioma]["Nombre"]}
                        </label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="entrada">
                        <label for="Contraseña">
                            {$mensajes[$idioma]["Contrasenia"]}
                        </label>
                        <input type= "password" name="password" required>
                    </div>
                </div>
                <div class="botones">
                    <input type="submit" name="Identificarse" value="{$mensajes[$idioma]["Identificarse"]}">
                    <a href="./registrarUsuario.php">
                        {$mensajes[$idioma]["Registrarse"]}
                    </a>
                </div>
        </form>
    HTML;
}
?>