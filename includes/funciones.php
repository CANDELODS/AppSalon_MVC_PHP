<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//
function esUltimo(string $actual, string $proximo): bool{
    if($actual !== $proximo){
        return true;
    }
    return false;
}

//Revisar Que El Usuario Esté Autenticado
function isAuth() : void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

//
function isAdmin() : void{
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}

//Iniciar La Superglobal De Session
function iniciarSesion(){
    if(!isset($_SESSION)) {
        session_start(); //Iniciamos La Super Global Session
    }
}