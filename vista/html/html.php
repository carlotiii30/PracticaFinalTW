<?php
include "codigoInicial.php";

session_start();
function htmlStart($titulo, $activo = '')
{
  __htmlIdiomas();
  __htmlInicio($titulo);
  __htmlEncabezado($activo);
}

function htmlEnd()
{
  __htmlPiepagina();
  __htmlFin();
}

function htmlNavGeneral($activo)
{
  if (!isset($_SESSION['autenticado'])) {
    htmlNavVisitante($activo);
  } else if ($_SESSION['rol'] == 'admin') {
    htmlNavAdmin($activo);
  } else {
    htmlNavColaborador($activo);
  }
}

function htmlNavVisitante($activo)
{
  global $mensajes;
  global $idioma;

  htmlNav('menu', [
    ['texto' => $mensajes[$idioma]["VerIncidencias"], 'url' => 'verIncidencias.php']
  ], $activo);
}

function htmlNavAdmin($activo)
{
  global $mensajes;
  global $idioma;

  htmlNav('menu', [
    ['texto' => $mensajes[$idioma]["VerIncidencias"], 'url' => 'verIncidencias.php'],
    ['texto' => $mensajes[$idioma]["NuevaIncidencia"], 'url' => 'nuevaIncidencia.php'],
    ['texto' => $mensajes[$idioma]["MisIncidencias"], 'url' => 'misIncidencias.php'],
    ['texto' => $mensajes[$idioma]["GestionUsuarios"], 'url' => 'gestionUsuarios.php'],
    ['texto' => $mensajes[$idioma]["Log"], 'url' => 'log.php'],
    ['texto' => $mensajes[$idioma]["GestionBBDD"], 'url' => 'gestionBD.php']
  ], $activo);
}

function htmlNavColaborador($activo)
{
  global $mensajes;
  global $idioma;

  htmlNav('menu', [
    ['texto' => $mensajes[$idioma]["VerIncidencias"], 'url' => 'verIncidencias.php'],
    ['texto' => $mensajes[$idioma]["NuevaIncidencia"], 'url' => 'nuevaIncidencia.php'],
    ['texto' => $mensajes[$idioma]["MisIncidencias"], 'url' => 'misIncidencias.php']
  ], $activo);
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

function htmlAside()
{
  echo '<aside>';
  if (!isset($_SESSION['autenticado'])) {
    __htmlLogin();
  } else {
    __htmlLogeado();
  }
}

function htmlPagLog($datos)
{
  global $mensajes;
  global $idioma;

  echo <<<HTML
  <div class='log'>
  <table>
      <tr>
      <th>{$mensajes[$idioma]["Fecha"]}</th>
      <th>{$mensajes[$idioma]["Accion"]}</th>
      </tr>
  HTML;

  foreach ($datos as $dato) {
    echo '<tr>';
    echo '<td class="log_fecha">' . htmlentities($dato['fecha']) . '</td>';
    echo '<td class="log_accion">' . htmlentities($dato['accion']) . '</td>';
    echo '</tr>';
  }

  echo <<<HTML
  </table>
  </div>
  HTML;
}

function htmlPagNuevaIncidencia()
{
  global $mensajesIncidencias;
  global $idioma;

  echo <<<HTML
    <div class="nueva">
        <h1 class="titulo">
            {$mensajesIncidencias[$idioma]["Nueva"]}
        </h1>
        <form method="post" action="./BD/procesarIncidencia.php">
            <h2 class="subtitulo">
                {$mensajesIncidencias[$idioma]["Datos"]}
            </h2>
            <div class="entrada">
                <label for="titulo">
                    {$mensajesIncidencias[$idioma]["Titulo"]}
                </label>
                <input name="titulo" value="">
                <label for="descripcion">
                    {$mensajesIncidencias[$idioma]["Descripcion"]}
                </label>
                <textarea name="descripcion" rows="4" cols="50"></textarea>
                <label for="lugar">
                    {$mensajesIncidencias[$idioma]["Lugar"]}
                </label>
                <input name="lugar" value="">
                <label for="keywords">
                    {$mensajesIncidencias[$idioma]["PalabrasClave"]}
                </label>
                <input name="keywords" value="">
            </div>
            <div class="botones">
                <input type="submit" value="{$mensajesIncidencias[$idioma]["Enviar"]}">
            </div>
        </form>
    </div>
    HTML;
}

function htmlPagVerIncidencias()
{
  global $mensajesCriterios;
  global $idioma;

  echo <<<HTML
  <div class="criterios">
    <h1 class="titulo">
        {$mensajesCriterios[$idioma]["Titulo"]}
    </h1>
    <form method="post" action="">
        <h2 class="subtitulo">
            {$mensajesCriterios[$idioma]["Criterios"]}
        </h2>
        <div class="entrada">
            <fieldset>
                <legend>
                    {$mensajesCriterios[$idioma]["Ordenar"]}
                </legend>
                <label><input type="radio" name="ordenar" value="Antiguedad">
                    {$mensajesCriterios[$idioma]["Antiguedad"]}
                </label>
                <label><input type="radio" name="ordenar" value="Mg">
                    {$mensajesCriterios[$idioma]["MeGustas"]}
                </label>
                <label><input type="radio" name="ordenar" value="NoMg">
                    {$mensajesCriterios[$idioma]["NoMeGustas"]}
                </label>
            </fieldset>
        </div>
        <div class="texto">
            <fieldset>
                <legend>
                    {$mensajesCriterios[$idioma]["Incidencias"]}
                </legend>
                <label for="texto">
                    {$mensajesCriterios[$idioma]["Texto"]}
                </label>
                <input name="texto" value="">

                <label for="lugar">
                    {$mensajesCriterios[$idioma]["Lugar"]}
                </label>
                <input name="lugar" value="">
            </fieldset>
        </div>
        <div class="entrada">
            <fieldset>
                <legend>
                    {$mensajesCriterios[$idioma]["Estado"]}
                </legend>
                <label> <input type="checkbox" name="estado[]" value="pendiente">
                    {$mensajesCriterios[$idioma]["Pendiente"]}
                </label>
                <label> <input type="checkbox" name="estado[]" value="comprobada">
                    {$mensajesCriterios[$idioma]["Comprobada"]}
                </label>
                <label> <input type="checkbox" name="estado[]" value="tramitada">
                    {$mensajesCriterios[$idioma]["Tramitada"]}
                </label>
                <label> <input type="checkbox" name="estado[]" value="irresoluble">
                    {$mensajesCriterios[$idioma]["Irresoluble"]}
                </label>
                <label> <input type="checkbox" name="estado[]" value="resuelta">
                    {$mensajesCriterios[$idioma]["Resuelta"]}
                </label>
            </fieldset>
        </div>
        <div class="botones">
            <input type="submit" value="{$mensajesCriterios[$idioma]["Aplicar"]}">
        </div>
    </form>
  </div>
  HTML;
}

function htmlPagMisIncidencias($datos)
{
  global $mensajesIncidencias;
  global $idioma;

  echo <<<HTML
  <div class='log'>
  <table>
      <tr>
      <th>{$mensajesIncidencias[$idioma]["Titulo"]}</th>
      <th>{$mensajesIncidencias[$idioma]["Descripcion"]}</th>
      <th>{$mensajesIncidencias[$idioma]["Lugar"]}</th>
      <th>{$mensajesIncidencias[$idioma]["PalabrasClave"]}</th>
      </tr>
  HTML;

  if ($datos != null) {
    foreach ($datos as $dato) {
      echo '<tr>';
      echo '<td class="inc_titulo">' . htmlentities($dato['titulo']) . '</td>';
      echo '<td class="inc_desc">' . htmlentities($dato['descripcion']) . '</td>';
      echo '<td class="inc_lugar">' . htmlentities($dato['lugar']) . '</td>';
      echo '<td class="inc_keyword">' . htmlentities($dato['keywords']) . '</td>';
      echo '</tr>';
    }
  }

  echo <<<HTML
  </table>
  </div>
  HTML;
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

function __htmlLogin()
{
  global $mensajes;
  global $idioma;

  echo <<<HTML
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

function __htmlLogout()
{
  session_destroy();
  header("Location: index.php");
  exit;
}

function __htmlLogeado()
{
  global $mensajes;
  global $idioma;

  echo <<<HTML
    <div class="login">
      <p>{$_SESSION["nombreUsuario"]}</p>
      <p>{$_SESSION["rol"]}</p>
      <form method="post" action="">
        <input type="submit" name="editar" value="{$mensajes[$idioma]["Editar"]}">
        <input type="submit" name="logout" value="{$mensajes[$idioma]["Desconectar"]}">
      </form>
    </div>
    HTML;

  // Verificar si se ha enviado el formulario de logout
  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    __htmlLogout();
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["editar"])) {
    # Página que muestra todos los datos del usuario, una vez dentro, se editan.
    header("Location: modificarUsuario.php");
  }

}

?>