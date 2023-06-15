<?php
/**
 * Fichero con las funciones relacionadas con la gestión de los usuarios.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

include "codigoInicial.php";

/**
 * Función para visualizar la página de gestión de usuarios.
 * Contiene un formulario con dos botones para indicar la opción que quieres hacer:
 *  - Ver el listado de usuarios.
 *  - Registrar un usuario nuevo.
 * 
 * Podemos editar o borrar los usuarios.
 * 
 * Para dar formato a los usuarios, se llama a la función ___formatoUsuario dentro de un 
 * bucle que recorre a todos los usuarios de la tabla usuarios.
 */
function htmlPagGestionUsuarios()
{
  echo <<<HTML
  <div class="principalGestion">
    <div class="gestionUsuarios">
      <form method="post" action="">
          <div class="botones">
              <input type="submit" name="listado" value="Listado">
              <input type="submit" name="nuevo" value="Registrar nuevo usuario">
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
      $datos = $db->query($sql);

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
    } else if (isset($_POST["editar"])) {
      modificarUsuario($idUsuario);
    }

    // Desconexión
    desconexion($db);
  }
}

/**
 * Función para dar formato a los usuarios.
 * 
 * @param array $usuario Datos del usuario.
 * @param mysqli $db Objeto mysqli que representa la conexión con la base de datos.
 * 
 * Hay un campo oculto para obtener el id del usuario.
 */
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
        <div class="opciones">
                <form method="post" action="">
    HTML;

  echo '<input type="hidden" name="usuario" value="' . $id . '">';

  echo <<<HTML
                <button name="editar">
                      <img src="vista/imagenes/editar.png">
                </button>
                <button name="borrar">
                    <img src="vista/imagenes/borrar.png">
                </button>
              </form>
            </div>
          </div>
    HTML;

}

/**
 * Función para borrar un usuario de la tabla de usuarios.
 * 
 * @param int $id ID del usuario que se va a borrar.
 * @param mysqli $db Objeto mysqli que representa la conexión con la base de datos.
 */
function borrarUsuario($id, $db) {

    $sql = "DELETE FROM usuarios WHERE id = $id";

    if ($db->query($sql)) {
        insertarLog("El usuario $id ha sido eliminado.", $db);
        $_SESSION['mensaje'] = "El usuario seleccionado ha sido eliminado. ¡¿Qué habrá hecho?!";
    }
    else {
        $_SESSION['mensaje'] = "El usuario seleccionado no se ha podido borrar. Parece que tiene una segunda oportunidad.";
    }

    header('Location: index.php');
    exit;
}

?>