<?php
    require('vista/html/html.php');     // Maquetado de página

    // ************* Inicio de la página
    htmlStart('Sal y quéjate'); 
    htmlNavAdmin($mensajes[$idioma]["Log"]);
    htmlPagInicio();
    htmlAside(false);
    htmlEnd();

    /*
        Sale directamente la tabla de logs:
            (fecha) (accion)
        
        Para insertar logs: (la tabla ya está creada)
            function insertarLog($accion) {
                $sql = "INSERT INTO logs (fecha, accion)
                        VALUES (NOW(), ?)";
                $stmt = $db -> prepare($sql);
                $stmt->bind_param("s", $accion);
                $stmt->execute();
                $stmt->close();
            }

     */
?>