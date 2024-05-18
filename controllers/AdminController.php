<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    public static function index(Router $router){
        if(!isset($_SESSION)) {
            session_start(); //Iniciamos La Super Global Session
        }

        isAdmin();
        $fecha = $_GET['fecha'] ?? date('Y-m-d'); //Desde buscador.js Obtuvimos La Fecha Y La Pasamos A La URL, Acá La Guardamos En Una Variable
        $fechas = explode('-', $fecha); //Dividimos La Fecha, Nos Retorna Un Arreglo Con 3 Posiciones
        //Comprobamos Que La Fecha Sea Correcta, Parametros Función chechdate: Mes, Día, Año
        if(!checkdate($fechas[1], $fechas[2], $fechas[0])){
            header('Location: /404');
        }

        //Consultar BD
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        $citas = AdminCita::SQL($consulta);
        

        $router->render('admin/index',[
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha

        ]);
    }
}