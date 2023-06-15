<?php
/**
 * Fichero con las funciones relacionadas con el log.
 * 
 * Autores: Carlota de la Vega Soriano y Manuel Vico Arboledas.
 */

 include "codigoInicial.php";

/**
 * Función para visualizar la página de log.
 * 
 * Recupera los datos de la tabla de logs y los muestra llamando a la funcion mostrarLog.
 * 
 */
function htmlPagLog()
{
  // Conexion
  $db = conexion();

  // Recuperacion de los datos
  $sql = "SELECT * FROM logs ORDER BY fecha DESC";
  $datos = $db->query($sql);

  // Desconexion
  desconexion($db);

  // Mostrar log
  mostrarLog($datos);
}

/**
 * Función para mostrar los datos del log.
 * 
 * @param array $datos Datos de la tabla de log.
 * 
 * Muestra una tabla con la fecha y la acción correspondiente a cada entrada de la tabla.
 */
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

?>