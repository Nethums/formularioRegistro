<?php

    $db_hostname = "localhost";
    $db_nombre = "evaluable_2021";
    $db_usuario = "root"; /* Es root ya que es un ejercicio de prueba. */
    $db_clave = "";

    try {
        $pdo = new PDO('mysql:host=' . $db_hostname . ';dbname=' . $db_nombre . '', $db_usuario, $db_clave);
        // Realiza el enlace con la BD en utf-8
        $pdo->exec("set names utf8");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);               
    } catch (PDOException $e) {
        echo "<p>Error: No puede conectarse con la base de datos.</p>\n";
        echo "<p>Error: " . $e->getMessage();
    }

?>