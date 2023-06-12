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

  echo "<section class='principal'>";

  if (isset($_SESSION['mensaje'])) {
    echo "<p> {$_SESSION['mensaje']} </p>"; // Mostrar el mensaje
    unset($_SESSION['mensaje']); // Eliminar el mensaje de la variable de sesión
  } else {
    echo <<<HTML
        <h1>
            {$mensajes[$idioma]["Bienvenida"]}
        </h1>
        <p>
            {$mensajes[$idioma]["Inicio"]}
        </p>
        <p>
            {$mensajes[$idioma]["Informacion"]}
        </p>
    HTML;
  }

  echo "</section>";

}

function htmlAside()
{
  echo '<aside>';
  if (!isset($_SESSION['autenticado'])) {
    __htmlLogin();
  } else {
    __htmlLogeado();
  }
  __htmlWidgets(1);
  __htmlWidgets(2);
}

function htmlPagLog()
{
  // Conexion
  $db = conexion();

  // Recuperacion de los datos
  $sql = "SELECT * FROM logs ORDER BY fecha DESC";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $datos = $stmt->get_result();

  // Desconexion
  desconexion($db);

  // Mostrar log
  mostrarLog($datos);
}

function mostrarLog($datos)
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
  global $insertada;
  global $confirmada;
  global $erroresIncidencia;

  if (isset($_POST["enviar"]) || isset($_POST["confirmar"]))
    insertarIncidencia();

  if (isset($_POST["enviarFoto"]))
    agregarFotoIncidencia();

  $readonly = $confirmada ? "readonly" : "";

  echo <<<HTML
    <div class="nueva">
        <h1 class="titulo">
            {$mensajesIncidencias[$idioma]["Nueva"]}
        </h1>
        <form method="post" action="">
            <h2 class="subtitulo">
                {$mensajesIncidencias[$idioma]["Datos"]}
            </h2>
            <div class="entrada">
            <label for="titulo">
              {$mensajesIncidencias[$idioma]["Titulo"]}
            </label>
    HTML;

  echo '<input type="text" name="titulo" value="' . (isset($_POST['titulo']) ? $_POST['titulo'] : '') . '"' . $readonly . '>';
  if (isset($erroresIncidencia['titulo'])) {
    echo '<p class="error">';
    echo $erroresIncidencia['titulo'];
    echo '</p>';
  }

  echo <<<HTML
    <label for="descripcion">
      {$mensajesIncidencias[$idioma]["Descripcion"]}
    </label>
HTML;

  echo '<textarea name="descripcion" rows="4" cols="50"' . $readonly . '>' . (isset($_POST['descripcion']) ? $_POST['descripcion'] : '') . '</textarea>';
  if (isset($erroresIncidencia['descripcion'])) {
    echo '<p class="error">';
    echo $erroresIncidencia['descripcion'];
    echo '</p>';
  }

  echo <<<HTML
<label for="lugar">
  {$mensajesIncidencias[$idioma]["Lugar"]}
</label>
HTML;

  echo '<input name="lugar" value="' . (isset($_POST['lugar']) ? $_POST['lugar'] : '') . '"' . $readonly . '>';
  if (isset($erroresIncidencia['lugar'])) {
    echo '<p class="error">';
    echo $erroresIncidencia['lugar'];
    echo '</p>';
  }

  echo <<<HTML
<label for="keywords">
  {$mensajesIncidencias[$idioma]["PalabrasClave"]}
</label>
HTML;

  echo '<input name="keywords" value="' . (isset($_POST['keywords']) ? $_POST['keywords'] : '') . '"' . $readonly . '>';
  if (isset($erroresIncidencia['keywords'])) {
    echo '<p class="keywords">';
    echo $erroresIncidencia['keywords'];
    echo '</p>';
  }

  echo "</div";

  if (!$insertada) {
    if (!$confirmada) {
      echo <<<HTML
            <div class="botones">
                <input type="submit" name="enviar" value="{$mensajesIncidencias[$idioma]['Enviar']}">
            </div>
          </form>
          HTML;
    } else {
      echo <<<HTML
            <div class="botones">
                <input type="submit" name="confirmar" value="Confirmar">
            </div>
          </form>
          HTML;
    }
  } else {
    echo <<<HTML
            </form>
            <form method="POST" action="" enctype="multipart/form-data">
              <div class="entrada">
                <label for="images">
                  Foto
                </label>
                <input type="file" name="images">
              </div>
              <div class="botones">
                <input type="submit" value="{$mensajesIncidencias[$idioma]['Enviar']}" name='enviarFoto'">
              </div>
            </form>
          HTML;
  }

  echo <<<HTML
    </div>
    HTML;
}

function htmlPagVerIncidencias($pagina)
{
  global $mensajesCriterios;
  global $idioma;
  global $incidencias;

  echo <<<HTML
  <div class="incidencias">
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
              <input type="hidden" name="pagina" value="$pagina">
          </div>
      </form>
    </div>
  HTML;

  if (isset($incidencias)) {
    mostrarIncidencias($incidencias);
  }
  echo '</div>';

}

function htmlPagMisIncidencias()
{
  // Conexion
  $db = conexion();

  // Recuperacion de datos de la base de datos
  $idUsuario = $_SESSION['idUsuario'];
  $sql = "SELECT * FROM incidencias WHERE idUsuario = ? ORDER BY fecha DESC";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", $idUsuario);
  $stmt->execute();
  $datos = $stmt->get_result();


  // Desconexión
  desconexion($db);

  // Mostrar incidencias
  echo "<div class='mis-incidencias'>";
  mostrarIncidencias($datos);
  echo "</div>";
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
                  <div class="botones">
                      <input type="submit" name="Identificarse" value="{$mensajes[$idioma]["Identificarse"]}">
                      <a href="./registrarUsuario.php">
                          {$mensajes[$idioma]["Registrarse"]}
                      </a>
                  </div>
                </div>
        </form>
    HTML;
}

function __htmlLogout()
{
  session_destroy();
  $db = conexion();
  insertarLog("El usuario {$_SESSION['nombreUsuario']} ha cerrado la sesión", $db);
  desconexion($db);
  echo "<meta http-equiv='refresh' content='0;url=./index.php'>"; //Para redirigir y no usar el header
  exit;
}

function __htmlLogeado()
{
  global $mensajes;
  global $idioma;

  // Verificar si se ha enviado el formulario.
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["logout"])) {
      __htmlLogout();
      exit;
    }
  }

  echo <<<HTML
    <div class="logeado">
        <div class="imagen-usuario">
    HTML;

  $db = conexion();
  descargarFoto('usuarios', $_SESSION["idUsuario"], $db);
  desconexion($db);

  echo <<<HTML
      </div>
      <p>{$_SESSION["nombreUsuario"]}</p>
      <p class="rol">{$_SESSION["rol"]}</p>
      <div class="botoneslogueado">
        <form method="post" action="./modificarUsuario.php">
          <input type="hidden" name="usuario" value="{$_SESSION["idUsuario"]}">
          <input type="submit" name="editar" value="{$mensajes[$idioma]["Editar"]}">
        </form>
        <form method="post" action="">
          <!--<a href="modificarUsuario.php">{$mensajes[$idioma]["Editar"]}</a>-->
          <input type="submit" name="logout" value="{$mensajes[$idioma]["Desconectar"]}">
        </form>
      </div>
    </div>
    HTML;
}


function mostrarIncidencias($incidencias)
{
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Posibilidades del formulario.
    if (isset($_POST["incidencia"])) {
      $incidencia = $_POST["incidencia"];
      $puedeVotar = puedeValorar($incidencia);

      if (isset($_POST["editar"])) {

      } else if (isset($_POST["borrar"])) {
        borrarIncidencia($incidencia);
      } else if (isset($_POST["sumar"]) && $puedeVotar) {
        valoracion($incidencia, "sumar");
      } else if (isset($_POST["restar"]) && $puedeVotar) {
        valoracion($incidencia, "restar");
      }
    }

    if (isset($_POST["comentario"])) {
      $comentario = $_POST["comentario"];

      if (isset($_POST["borrarComentario"]))
        borrarComentario($comentario);
    }
  }

  foreach ($incidencias as $dato) {
    __formatoIncidencia($dato);

    if (isset($_POST["comentar"])) {
      if ($_POST["incidencia"] == $dato["id"]) {
        $_SESSION["idIncidencia"] = $dato["id"];
        htmlPagComentarios();
      }
    } else if (isset($_SESSION["idIncidencia"])) {
      if ($_SESSION["idIncidencia"] == $dato["id"]) {
        if (!isset($_SESSION["comentarioInsertado"]) || (isset($_SESSION["comentarioInsertado"]) && !$_SESSION["comentarioInsertado"])) {
          htmlPagComentarios();
        }
      }
    }
    if (isset($_SESSION["comentarioInsertado"]) && $_SESSION["comentarioInsertado"]) {
      unset($_SESSION["comentarioInsertado"]);
      unset($_SESSION["idIncidencia"]);

      // Redirigimos.
      echo "<meta http-equiv='refresh' content='0;url=./index.php'>"; //Para redirigir y no usar el header
      exit;
    }
  }
}


function __formatoIncidencia($incidencia)
{
  global $mensajesIncidencias;
  global $idioma;

  $nombre = obtenerNombreUsuario($incidencia["idUsuario"]);
  $valoraciones = obtenerValoraciones($incidencia["id"]);

  echo <<<HTML
    <div class="incidencia">
      <h1>{$incidencia["titulo"]}</h1>
      <div class="cabecera-incidencia">
        <ul>
          <li> <div class="cabecera-texto"> {$mensajesIncidencias[$idioma]["Lugar"]}: </div> {$incidencia["lugar"]}</li>
          <li> <div class="cabecera-texto"> {$mensajesIncidencias[$idioma]["Fecha"]}: </div> {$incidencia["fecha"]}</li>
          <li> <div class="cabecera-texto"> {$mensajesIncidencias[$idioma]["Creador"]}: </div> {$nombre} </li>
          <li> <div class="cabecera-texto"> {$mensajesIncidencias[$idioma]["PalabrasClave"]}: </div> {$incidencia["keywords"]}</li>
          <li> <div class="cabecera-texto"> {$mensajesIncidencias[$idioma]["Estado"]}: </div> {$incidencia["estado"]}</li>
          <li> <div class="cabecera-texto"> {$mensajesIncidencias[$idioma]["Valoraciones"]}: </div> {$valoraciones["positivas"]} | {$valoraciones["negativas"]}</li>
        </ul>
      </div>
      <div class="cuerpo">
        <p> {$incidencia["descripcion"]} </p>
  HTML;

  // Fotos.
  mostrarFotos($incidencia['id']);

  echo <<<HTML
      </div>
      <div class="comentarios">
  HTML;

  mostrarComentarios($incidencia["id"]);

  echo <<<HTML
  </div>
  <div class="opiniones">
  HTML;

  if (isset($_SESSION['autenticado'])) {
    if ($_SESSION['idUsuario'] == $incidencia["idUsuario"] || $_SESSION['rol'] == "admin") {

      echo <<<HTML
        <form method="post" action="./editarIncidencia.php">
          <div class="botones">
            <input type="hidden" name="editarInc" value="{$incidencia["id"]}">
            <button name="editar">
              <img src="vista/imagenes/editar.png">
            </button>
          </div>
        </form>
    HTML;
    }
  }

  echo "<form method='post' action=''>";
  echo "<div class='botones'>";

  if (isset($_SESSION['autenticado'])) {
    if ($_SESSION['idUsuario'] == $incidencia["idUsuario"] || $_SESSION['rol'] == "admin") {

      echo <<<HTML
            <button name="borrar">
                <img src="vista/imagenes/borrar.png">
            </button>
    HTML;

    }
  }

  echo '<input type="hidden" name="incidencia" value="' . $incidencia["id"] . '">';

  echo <<<HTML
            <button name="sumar">
                <img src="vista/imagenes/verde.png">
            </button>
            <button name="restar">
                <img src="vista/imagenes/rojo.png">
            </button>
            <button name="comentar">
                <img src="vista/imagenes/comentario.png">
            </button>
          </div>
        </form>
      </div>
    </div>
  HTML;

}

// Para visualizar las fotos.
function mostrarFotos($id)
{
  $db = conexion();

  $sql = "SELECT foto FROM fotos WHERE idIncidencia = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();


  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $foto = $row['foto'];

      $fotoData = base64_encode($foto);
      $src = 'data:image/jpeg;base64,' . $fotoData;
      $imageData = "<img src='$src' alt='Foto'>";

      echo $imageData;
    }
  }

  desconexion($db);
}


// Formato para los comentarios
function mostrarComentarios($id)
{
  $db = conexion();

  $sql = "SELECT c.id, c.comentario, c.fecha, c.idUsuario, u.nombre, u.apellidos FROM comentarios c LEFT JOIN usuarios u ON c.idUsuario = u.id WHERE c.idIncidencia = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();


  if ($result && $result->num_rows > 0) {
    $fila = 0; // Variable de contador para filas

    while ($row = $result->fetch_assoc()) {
      $idComentario = $row["id"];
      $comentario = $row["comentario"];
      $fecha = $row["fecha"];
      $idUsuario = $row["idUsuario"];
      $nombreUsuario = $row["nombre"];
      $apellidos = $row["apellidos"];
      $fila++;

      // Determinar clase CSS para la fila actual
      $fila_class = ($fila % 2 == 0) ? 'fila-par' : 'fila-impar';

      // Mostrar el nombre de usuario, comentario y fecha con la clase CSS correspondiente
      echo "<div class='comentarios'>
              <div class='$fila_class'>
                <div class='datos'>
                  <div class='nombre'> " . ($idUsuario == 0 ? "Anónimo" : "$nombreUsuario $apellidos") . "</div>
                  <div class='fecha'> $fecha </div>
                </div>
                <div class='comentario'> $comentario </div>";

      if (isset($_SESSION['autenticado'])) {
        if ($_SESSION['idUsuario'] == $idUsuario || $_SESSION['rol'] == "admin") {

          echo "<div class='opciones'>
                      <form method='post' action=''>
                      <input type='hidden' name='comentario' value='$idComentario'>
                        <button name='borrarComentario'>
                          <img src='vista/imagenes/borrar.png'>
                        </button>
                      </form>
                    </div>";
        }
      }
      echo "</div>
              </div>";
    }
  }

  desconexion($db);
}

function htmlPagGestionUsuarios()
{
  global $idioma;
  global $mensajes;

  echo <<<HTML
  <div class="principalGestion">
    <div class="gestionUsuarios">
      <form method="post" action="">
          <div class="botones">
              <input type="submit" name="listado" value="{$mensajes[$idioma]['Listado']}">
              <input type="submit" name="nuevo" value="{$mensajes[$idioma]['Registrar']}">
          </div>
      </form>
    </div>
HTML;

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["listado"])) {
      // Conexion
      $db = conexion();

      // Recuperación de datos
      $sql = "SELECT * FROM usuarios";
      $stmt = $db->prepare($sql);
      $stmt->execute();
      $datos = $stmt->get_result();


      // Formato de usuarios
      foreach ($datos as $dato) {
        __formatoUsuario($dato, $db);
      }

      // Desconexión
      desconexion($db);

    } else if (isset($_POST["nuevo"])) {
      header("Location: registrarUsuario.php");
      exit;
    }
  }
  echo '</div>';

  if (isset($_POST["usuario"])) {
    $idUsuario = $_POST["usuario"];

    // Conexion
    $db = conexion();

    if (isset($_POST["borrar"])) {
      borrarUsuario($idUsuario, $db);
    }

    // Desconexión
    desconexion($db);
  }
}

function __formatoUsuario($usuario, $db)
{
  global $mensajesRegistro;
  global $idioma;

  echo <<<HTML
          <div class="usuario">
        <div class="foto">
    HTML;

  $id = $usuario["id"];

  descargarFoto("usuarios", $id, $db);

  echo <<<HTML
        </div>
        <div class="contenido">
            <ul>
                <div class="fila">
                  <li><div class="etiqueta">{$mensajesRegistro[$idioma]["Nombre"]}:</div> {$usuario["nombre"]} {$usuario["apellidos"]}</li>
                  <li><div class="etiqueta">{$mensajesRegistro[$idioma]["Email"]}:</div> {$usuario["email"]}</li>
                </div>
                <div class="fila">
                  <li><div class="etiqueta">{$mensajesRegistro[$idioma]["Direccion"]}:</div> {$usuario["direccion"]}</li>
                  <li><div class="etiqueta">{$mensajesRegistro[$idioma]["Telefono"]}:</div> {$usuario["telefono"]}</li>
                </div>
                <div class="fila">
                  <li><div class="etiqueta">Rol:</div> {$usuario["rol"]}</li>
                  <li><div class="etiqueta">{$mensajesRegistro[$idioma]["Estado"]}:</div> {$usuario["estado"]}</li>
                </div>
            </ul>
        </div>
    <!--</div>-->
        <div class="opciones">
                <form method="post" action="">
    HTML;

  echo '<input type="hidden" name="usuario" value="' . $id . '">';

  echo <<<HTML
                
                <button name="borrar">
                    <img src="vista/imagenes/borrar.png">
                </button>
              </form>
              <form method="post" action="./modificarUsuario.php">
  HTML;
  echo '<input type="hidden" name="usuario" value="' . $id . '">';
  echo <<<HTML
                <button name="editar">
                      <img src="vista/imagenes/editar.png">
                </button>
              </form>
            </div>
          </div>
    HTML;

}

function htmlPagGestionBD()
{
  global $mensajesBackup;
  global $idioma;

  echo <<<HTML
    <div class="gestion">
        <form method="post" action="./BD/procesarCopia.php">
            <div class="botones">
                <input type="submit" name="descargar" value="{$mensajesBackup[$idioma]['Descargar']}">
                <input type="submit" name="restaurar" value="{$mensajesBackup[$idioma]['Restaurar']}">
                <input type="submit" name="borrar" value="{$mensajesBackup[$idioma]['Borrar']}">
            </div>
    HTML;

  if (isset($_POST["borrar"])) {
    echo <<<HTML
          <div class="seguridad">
              <p>{$mensajesBackup[$idioma]['Seguridad']}</p>
              <label for="confirmar">{$mensajesBackup[$idioma]['Confirmar']}:</label>
              <input type="text" name="confirmar" id="confirmar">
              <input type="submit" name="confirmar_borrar" value="Estoy segurísimo">
          </div>
  HTML;
  }

  echo "</form></div>";
}

function htmlPagComentarios()
{
  echo <<<HTML
      <div class="comentar-incidencia ">
        <form method="POST" action="">
          <label for="comentario">
            Comentario:
          </label>
          <textarea name="comentario" rows="4" cols="50"></textarea>
          <div class="botones">
            <input type="submit" name="enviarComentario" value="Enviar comentario">
          </div>
        </form>
      </div>
    HTML;

  if (isset($_POST["enviarComentario"]) && empty($_POST['comentario'])) {
    echo "<p class='error'>No puede insertar un comentario vacío. Por favor, introduzca un comentario.</p>";
  }

  if (isset($_POST["enviarComentario"])) {
    insertarComentario();
  }
}


function insertarComentario()
{
  // Conexión con la BBDD
  $db = conexion();

  // Verificar si se pudo establecer la conexión
  if ($db === false) {
    die("Error al conectar con la base de datos");
  }

  // Id del usuario
  $id = isset($_SESSION['idUsuario']) ? $_SESSION['idUsuario'] : 0;

  // Nombre
  $nombre = obtenerNombreUsuario($id);

  // Id de la incidencia
  $idIncidencia = $_SESSION['idIncidencia'];

  $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';

  // Verificar si el comentario no está vacío
  if (!empty($comentario)) {
    $nombreUsuario = isset($nombre) ? $nombre : 'Anónimo';

    // Preparar la consulta SQL con sentencias preparadas
    $sql = "INSERT INTO comentarios (idUsuario, idIncidencia, comentario, fecha) VALUES (?, ?, ?, NOW())";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
      die("Error al preparar la consulta SQL");
    }

    // Vincular los parámetros a la consulta SQL
    $stmt->bind_param("iis", $id, $idIncidencia, $comentario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
      $_SESSION["comentarioInsertado"] = true;

      insertarLog("El usuario $nombreUsuario ha comentado en la incidencia con id $idIncidencia", $db);
      // Mostrar mensaje de éxito
      $_SESSION['mensaje'] = "¡Enhorabuena! Su comentario ha sido añadido con éxito. Esperamos que sea útil para la comunidad su aportación.";
    } else {
      // Manejar el error de la consulta SQL
      echo "Error al ejecutar la consulta SQL: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $db->close();
  }
}


function __htmlWidgets($opcion)
{
  $db = conexion();

  if ($opcion == 1) {
    $sql = "SELECT u.nombre, COUNT(i.id) AS total_incidencias
          FROM usuarios u
          INNER JOIN incidencias i ON u.id = i.idUsuario
          GROUP BY u.id
          ORDER BY total_incidencias DESC
          LIMIT 3";
  } else if ($opcion == 2) {
    $sql = "SELECT u.nombre, COUNT(c.id) AS total_incidencias
        FROM usuarios u
        INNER JOIN comentarios c ON u.id = c.idUsuario
        GROUP BY u.id
        ORDER BY total_incidencias DESC
        LIMIT 3";
  }

  $stmt = $db->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
    $top = array();
    while ($row = $result->fetch_assoc()) {
      $top[] = $row;
    }
  }
  desconexion($db);

  if (isset($top))
    __htmlWidgetsFormato($top, $opcion);
}

function __htmlWidgetsFormato($top, $opcion)
{
  global $mensajes;
  global $idioma;

  if ($opcion == 1) {
    $titulo = $mensajes[$idioma]["Añaden"];
  } else if ($opcion == 2) {
    $titulo = $mensajes[$idioma]["Comentan"];
  }
  echo "<div class='widget'>";
  echo "<h1>{$titulo}</h1>";
  echo "<ol>";
  foreach ($top as $elem) {
    echo "<li>({$elem['total_incidencias']}) {$elem['nombre']}</li>";
  }
  echo "</ol>";
  echo "</div>";
}

function modificarUsuario($idUsuario)
{
  global $mensajesRegistro, $idioma;
  global $cambiosValidados;
  global $erroresCambios;

  $erroresCambios = array();

  $datos = guardarCambios($idUsuario);

  // Conexión con la BBDD
  if (is_string($db = conexion())) {
    $msg_err = $db;
  } else {
    $id = $idUsuario;
    // Consulta SQL para obtener los datos del usuario
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
      $usuario = $result->fetch_assoc();

      $disabled = $cambiosValidados ? "disabled" : "";
      $readonly = $cambiosValidados ? "readonly" : "";
      $esAdmin = $_SESSION['rol'] == "admin" ? "" : "disabled";

      echo <<<HTML
          <div class="modificar">
              <form method="POST" action=""  enctype="multipart/form-data">
                  <div class="entrada">
                      <label for="foto">
                          <input type="file" name="images" {$disabled}> 
                      </label>
                      <label for="nombre">
                          {$mensajesRegistro[$idioma]["Nombre"]}
                      </label>
          HTML;
      echo '<input type="text" name="nombre" value="' . (!$cambiosValidados ? $usuario['nombre'] : $datos['nombre']) . '" ' . $readonly . '>';
      if (isset($erroresCambios['nombre'])) {
        echo '<p class="error">';
        echo $erroresCambios['nombre'];
        echo '</p>';
      }

      echo '<label for="apellidos">';
      echo $mensajesRegistro[$idioma]["Apellidos"];
      echo '</label>';
      echo '<input type="text" name="apellidos" value="' . (!$cambiosValidados ? $usuario['apellidos'] : $datos['apellidos']) . '" ' . $readonly . '>';
      if (isset($erroresCambios['apellidos'])) {
        echo '<p class="error">';
        echo $erroresCambios['apellidos'];
        echo '</p>';
      }

      echo '<label for="email">';
      echo $mensajesRegistro[$idioma]["Email"];
      echo '</label>';
      echo '<input type="email" name="email" value="' . (!$cambiosValidados ? $usuario['email'] : $datos['email']) . '" ' . $readonly . '>';
      if (isset($erroresCambios['email'])) {
        echo '<p class="error">';
        echo $erroresCambios['email'];
        echo '</p>';
      }

      echo '<label for="telefono">';
      echo $mensajesRegistro[$idioma]["Telefono"];
      echo '</label>';
      echo '<input type="text" name="telefono" value="' . (!$cambiosValidados ? $usuario['telefono'] : $datos['telefono']) . '" ' . $readonly . '>';
      if (isset($erroresCambios['telefono'])) {
        echo '<p class="error">';
        echo $erroresCambios['telefono'];
        echo '</p>';
      }

      echo '<label for="direccion">';
      echo $mensajesRegistro[$idioma]["Direccion"];
      echo '</label>';
      echo '<input type="text" name="direccion" value="' . (!$cambiosValidados ? $usuario['direccion'] : $datos['direccion']) . '" ' . $readonly . '>';
      if (isset($erroresCambios['direccion'])) {
        echo '<p class="error">';
        echo $erroresCambios['direccion'];
        echo '</p>';
      }

      echo '<div class="contrasenia-contenedor">';
      echo '<div class="campo">';
      echo '<label for="password1">';
      echo $mensajesRegistro[$idioma]["Contrasenia"];
      echo '</label>';
      echo '<input class="password1" type="password" name="password1" value="' . ($cambiosValidados ? $datos['password1'] : "") . '" ' . $readonly . '>';
      if (isset($erroresCambios['contraseña'])) {
        echo '<p class="error">';
        echo $erroresCambios['contraseña'];
        echo '</p>';
      }
      echo '</div>';
      echo '<div class="campo">';
      echo '<label for="password2">';
      echo $mensajesRegistro[$idioma]["Confirmar"];
      echo '</label>';
      echo '<input class="password2" type="password" name="password2" value="' . ($cambiosValidados ? $datos['password1'] : "") . '" ' . $readonly . '>';
      echo '</div>';
      echo '</div>';

      echo '<label for="estado">';
      echo $mensajesRegistro[$idioma]["Estado"];
      echo '</label>';
      echo '<select name="estado" ' . $esAdmin . $disabled . '>';
      echo '<option value="activo"' . (!$cambiosValidados ? ($usuario['estado'] == 'activo' ? ' selected' : '') : ($datos['estado'] == 'activo' ? ' selected' : '')) . '>Activo</option>';
      echo '<option value="inactivo"' . (!$cambiosValidados ? ($usuario['estado'] == 'inactivo' ? ' selected' : '') : ($datos['estado'] == 'inactivo' ? ' selected' : '')) . '>Inactivo</option>';
      echo '</select>';
      if($disabled == "disabled"){
        echo $datos['estado'];
        echo '<input type="hidden" name="estado" value="' . $datos['estado'] . '">';
      }
      //echo '<input type="text" name="estado" value="' . ($usuario['estado']) . '"' . $esAdmin . $disabled . '>';

      echo '<label for="rol">Rol:</label>';
      echo '<select name="rol" ' . $esAdmin . $disabled . '>';
      echo '<option value="admin"' . (!$cambiosValidados ? ($usuario['rol'] == 'admin' ? ' selected' : '') : ($datos['rol'] == 'admin' ? ' selected' : '')) . '>Admin</option>';
      echo '<option value="colaborador"' . (!$cambiosValidados ? ($usuario['rol'] == 'colaborador' ? ' selected' : '') : ($datos['rol'] == 'colaborador' ? ' selected' : '')) . '>Colaborador</option>';
      echo '</select>';
      if($disabled == "disabled"){
        echo $datos['rol'];
        echo '<input type="hidden" name="rol" value="' . $datos['rol'] . '">';
      }
     // echo '<input type="text" name="rol" value="' . ($usuario['rol']) . '"' . $esAdmin . $disabled .'>';

      echo '<div class="botones">';
      if ($cambiosValidados == false) {
        echo '<input type="submit" name="cambiar" value="' . $mensajesRegistro[$idioma]["Cambios"] . '">';
      } else {
        echo '<input type="submit" name="confirmar" value="Confirmar cambios">';
        if ($datos['hayimagen'] == true) {
          echo ' <input type="hidden" name="imagen" value="imagen">';
        }
      }
      echo '</div>';
      echo '</div>';
      echo '</form>';
      echo '<div class="imagen-usuario">';
      descargarFoto("usuarios", $id, $db);
      echo '</div>';
      echo '</div>';
    } else {
      echo 'No se encontraron registros en la tabla usuario.';
    }
    // Desconectar de la BBDD (se puede omitir)
    desconexion($db);
  }

}

function htmlPagEditarIncidencia($idIncidencia)
{
  procesamientoEditar();
  __htmlEstadoIncidencia($idIncidencia);
  __htmlIncidencia($idIncidencia);
  __htmlFotosIncidencia($idIncidencia);
}

function __htmlEstadoIncidencia($idIncidencia)
{
  global $mensajesIncidencias;
  global $idioma;

  if (is_string($db = conexion())) {
    $msg_err = $db;
  } else {
    $id = $idIncidencia;

    // Comprobamos si es administrador.
    $admin = ($_SESSION['rol'] !== 'admin');

    // Consulta preparada SQL para obtener los datos del usuario
    $sql = "SELECT * FROM incidencias WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result && $result->num_rows > 0) {
      $incidencia = $result->fetch_assoc();
      $estado = $incidencia['estado'];
      echo <<<HTML
      <div class="editarIncidencia">
        <div class="estado">
            <form method="post" action="">
              <h2 class="subtitulo">
                {$mensajesIncidencias[$idioma]["Datos"]}
               </h2>
              <div class="entrada">
      HTML;

      echo '<label><input type="radio" name="estado" value="Pendiente"' . ($estado == 'Pendiente' ? ' checked' : '') . ($admin ? ' disabled' : '') . '>';
      echo 'Pendiente';
      echo '</label>';

      echo '<label><input type="radio" name="estado" value="Comprobada"' . ($estado == 'Comprobada' ? ' checked' : '') . ($admin ? ' disabled' : '') . '>';
      echo 'Comprobada';
      echo '</label>';

      echo '<label><input type="radio" name="estado" value="Tramitada"' . ($estado == 'Tramitada' ? ' checked' : '') . ($admin ? ' disabled' : '') . '>';
      echo 'Tramitada';
      echo '</label>';

      echo '<label><input type="radio" name="estado" value="Irresoluble"' . ($estado == 'Irresoluble' ? ' checked' : '') . ($admin ? ' disabled' : '') . '>';
      echo 'Irresoluble';
      echo '</label>';

      echo '<label><input type="radio" name="estado" value="Resuelta"' . ($estado == 'Resuelta' ? ' checked' : '') . ($admin ? ' disabled' : '') . '>';
      echo 'Resuelta';

      echo <<<HTML
                </label>
              </div>
              <div class="botones">
                <input type="submit" name= "modificarEstado" value="{$mensajesIncidencias[$idioma]["Enviar"]}">
                <input type="hidden" name="idIncidencia" value="$id">
              </div>
            </form>
        </div>
      HTML;
    } else {
      echo 'No se encontraron registros en la tabla incidencias.';
    }
    desconexion($db);
  }
}
function __htmlIncidencia($idIncidencia)
{
  global $mensajesIncidencias;
  global $idioma;
  global $confirmada;
  global $erroresIncidencia;

  if (is_string($db = conexion())) {
    $msg_err = $db;
  } else {
    $id = $idIncidencia;
    // Consulta SQL para obtener los datos de la incidencia
    $sql = "SELECT * FROM incidencias WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result && $result->num_rows > 0) {
      $incidencia = $result->fetch_assoc();
      $readonly = $confirmada ? "readonly" : "";

      echo <<<HTML
      <form method="post" action="">
        <div class="entrada">
          <h2 class="subtitulo">
            {$mensajesIncidencias[$idioma]["Datos"]}
          </h2>
          <label for="titulo">
            {$mensajesIncidencias[$idioma]["Titulo"]}
          </label>
      HTML;
      echo '<input type="text" name="titulo" value="' . ($confirmada ? $_POST['titulo'] : $incidencia['titulo']) . '"' . $readonly . '>';
      if (isset($erroresIncidencia['titulo'])) {
        echo '<p class="error">';
        echo $erroresIncidencia['titulo'];
        echo '</p>';
      }
      echo <<<HTML
          <label for="descripcion">
            {$mensajesIncidencias[$idioma]["Descripcion"]}
          </label>
      HTML;
      echo '<textarea name="descripcion" rows="4" cols="50"' . $readonly . '>' . ($confirmada ? $_POST['descripcion'] : $incidencia['descripcion']) . '</textarea>';
      if (isset($erroresIncidencia['descripcion'])) {
        echo '<p class="error">';
        echo $erroresIncidencia['descripcion'];
        echo '</p>';
      }
      echo <<<HTML
          <label for="lugar">
            {$mensajesIncidencias[$idioma]["Lugar"]}
          </label>
      HTML;
      echo '<input name="lugar" value="' . ($confirmada ? $_POST['lugar'] : $incidencia['lugar']) . '"' . $readonly . '>';
      if (isset($erroresIncidencia['lugar'])) {
        echo '<p class="error">';
        echo $erroresIncidencia['lugar'];
        echo '</p>';
      }
      echo <<<HTML
          <label for="keywords">
            {$mensajesIncidencias[$idioma]["PalabrasClave"]}
          </label>
      HTML;
      echo '<input name="keywords" value="' . ($confirmada ? $_POST['keywords'] : $incidencia['keywords']) . '"' . $readonly . '>';
      if (isset($erroresIncidencia['keywords'])) {
        echo '<p class="keywords">';
        echo $erroresIncidencia['keywords'];
        echo '</p>';
      }
      echo <<<HTML
        </div>
        <div class="botones">
      HTML;
      if (!$confirmada) {
        echo '<input type="submit" name="editar" value="' . $mensajesIncidencias[$idioma]["Enviar"] . '">';
      } else {
        echo '<input type="submit" name="confirmar" value="' . $mensajesIncidencias[$idioma]["Confirmar"] . '">';
        echo '<input type="hidden" name="idIncidencia" value="' . $id . '">';
      }
      echo <<<HTML
        </div>
      </form>
      HTML;
    } else {
      echo 'No se encontraron registros en la tabla incidencias.';
    }
    desconexion($db);
  }
}

function __htmlFotosIncidencia($idIncidencia)
{
  global $mensajesIncidencias;
  global $idioma;

  if (is_string($db = conexion())) {
    $msg_err = $db;
  } else {
    $id = $idIncidencia;
    // Consulta SQL para obtener los datos de la incidencia
    $sql = "SELECT * FROM fotos WHERE idIncidencia = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo <<<HTML
      <div class="adjuntas">
        <div class="entrada">
          <h2 class="subtitulo">
            Fotografías adjuntas
          </h2>
    HTML;

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $foto = base64_encode($row['foto']);
        $src = 'data:image/jpeg;base64,' . $foto;
        $imageData = "<img src='$src' alt='Foto'>";
        echo '<div class="foto">';
        echo $imageData;
        echo '<form method="post" action="">';
        echo '<div class="botones">';
        echo '<input type="submit" name="borrarFoto" value="Borrar foto">';
        echo '<input type="hidden" name="idFoto" value="' . $row['id'] . '">';
        echo '</div>';
        echo '</form>';
        echo '</div>';
      }

    }

    echo <<<HTML
          <form method="post" action="" enctype="multipart/form-data">
            <label for="foto">
              <input type="file" name="images"> 
            </label>
            <input type="submit" name="subir" value="Añadir fotografía">
            <input type="hidden" name="idIncidencia" value="$id">
          </form>
        </div>
      </div>
    </div>
    HTML;
  }
}

?>