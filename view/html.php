<?php

function htmlStart($titulo,$activo='') {
    __htmlIdiomas();
    __htmlInicio($titulo);
    __htmlEncabezado($activo);
  }
  
  function htmlEnd() {
    __htmlPiepagina();
    __htmlFin();
  }
  
  function htmlNavAdmin($activo){
    htmlNav('menu',[['texto'=>'Ver incidencias', 'url'=>'listado.php'],
                  ['texto'=>'Nueva incidencia', 'url'=>'listado_paginado.php'],
                  ['texto'=>'Mis incidencias', 'url'=>'listado_paginadoBotones.php'],
                  ['texto'=>'Gestión de usuarios', 'url'=>'buscarCiudad.php'],
                  ['texto'=>'Ver log', 'url'=>'addCiudad.php'],
                  ['texto'=>'Gestion de Base de Datos', 'url'=>'backup.php']],$activo);
  }

  function htmlNav($clase,$menu,$activo='') {
    echo "<nav class='$clase'>";
      echo "<ul>";  
        foreach ($menu as $elem) {
            echo "<li> <a ".($activo==$elem['texto']?"class='activo' ":'')."href='{$elem['url']}'>{$elem['texto']}</a> </li>";
        }
      echo "</ul>";
    echo '</nav>';
    __htmlContenidosIni();
  }

  function htmlPagInicio(){
    echo <<< HTML
    <section class="principal">
            <!-- Mostrar mensaje de bienvenida en el idioma seleccionado -->
            <h1>
                Bienvenida
            </h1>
            <p>
                Inicio
            </p>
            <p>
                Informacion
            </p>
    </section>
    HTML;
}

function htmlAside(){
  echo <<< HTML
  <aside>
            <form action="">
                <div class="login">
                    <div class="entrada">
                        <label for="nombre">
                            Nombre:
                        </label>
                        <input name="nombre" value="">
                    </div>
                    <div class="entrada">
                        <label for="Contraseña">
                            Contraseña:
                        </label>
                        <input name="contraseña" value="">
                    </div>
                </div>
                <div class="botones">
                    <input type="submit" name="Identificarse" value="identificarse">
                    <a href="./registrarse.html">
                        Registrarse
                    </a>
                </div>
            </form>
        </aside>
  HTML;
}
  
  


  
  
  // ******** Funciones privadas de este módulo
  
  function __htmlIdiomas(){
  echo <<< HTML
  <div class="elegirIdioma">
    <!-- Seleccionar idioma -->
    <img class="imgIdioma" src="./vista/imagenes/mundo_sf.png" alt="">
    <p>
        Lenguaje
    </p>

    <form method="get" action="">
        <div class="entrada">
            <select name="idioma">
                <option value="es">
                    Español
                </option>
                <option value="en" >
                    Inglés
                </option>
                <option value="fr">
                    Francés
                </option>
            </select>
        </div>
        <div class="botones">
            <input type="submit" name="aplicar" value="aplicar">
        </div>
    </form>
    
  </div>
  HTML;
  }

  // Cabecera de página web
  function __htmlInicio($titulo) {
  echo <<< HTML
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
  function __htmlContenidosIni() {
  echo '<main>';
  }
  
  // Encabezado
  function __htmlEncabezado($activo) {
  echo <<< HTML
  <div class='cabecera'>
    <img src="./vista/imagenes/SugQueRec.png" alt="">
    <h1>SAL Y QUÉJATE</h1>
  </div>
  HTML;
  }

  
  // Pie de página
  function __htmlPiepagina() {
  __htmlContenidosFin();
  echo <<< HTML
  <footer>
    <p>Trabajo final de Tecnologías Web. &copy; Carlota de la Vega Soriano y Manuel Vico Arboledas</p>
  </footer>
  HTML;
  }
  
  // Contenidos FIN
  function __htmlContenidosFin() {
  echo '</main>';
  }
  
  // Cierre de página web
  function __htmlFin() {
  echo '</body></html>';
  }

  function __htmlLogin(){

  }
?>
