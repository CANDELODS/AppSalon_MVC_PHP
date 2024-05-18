<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{
    public static function index(){
        $servicios = Servicio::all();
        //Los Arreglos Asociativos No Existen En Java Script Por Lo Cual Los Pasamos A Json Con json_encode
        echo json_encode($servicios);
    }

    public static function guardar(){
        //Almacena La Cita Y Devuelve El Id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        //Almacena Las Citas Y Los Servicios (Relación Muchos A Muchos)
        $idServicios = explode(",", $_POST['servicios']); //Separamos Los Id De Los Servicios

        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        //Retornamos Una Respuesta
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            //En Caso De Que Quieran Cambiar El Value Del Input
            if(filter_var($id,FILTER_VALIDATE_INT)){
                $cita = Cita::find($id);
            }
            if($cita){
                $cita->eliminar();
            }     
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}