<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        //Inicializamos El Arreglo Para Las Alertas
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            //Llenamos El Arreglo De Alertas Con La Información De Post (Model/Usuario: Linea 59)
            $alertas = $auth->validarLogin();
            //Como No Hay Alertas, Significa Que Se Llenaron Los 2 Campos (Email, Password)
            if(empty($alertas)){
                //Comprobar Que Exista El Usuario
                $usuario= Usuario::where('email', $auth->email);//(Ver Active-Record Linea 135)
                if($usuario){
                    //Verificar El Password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){ //(Ver Model/Ususario: Linea 110)
                        //AUTENTICAR AL USUARIO
                        if(!isset($_SESSION)) {
                            session_start(); //Iniciamos La Super Global Session
                        }
                        //Creamos Y Llenamos La Super Global Session
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        //Redireccionamiento
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{ //Si No Es Admin Entonces
                            header('Location: /cita');
                        }
                    } //Fin Verificar Password

                }//Fin If($usuario)

                //Else de If($usuario)
                else{ 
                    Usuario::setAlerta('error', 'Usuario No Encontrado');
                }
            } //Fin IF(empty($alertas))
        } //Fin if($_SERVER['REQUEST_METHOD'] === 'POST')

        //Obtenemos Las Alertas
        $alertas = Usuario::getAlertas();//(Ver Active Record Linea 28)
        //Renderizamos La Vista
        $router->render('auth/login',[
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        if(!isset($_SESSION)) {
            session_start(); //Iniciamos La Super Global Session
        }

        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router){
        //Inicializamos El Arreglo Para Las Alertas
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);//(Ver Active Record Linea 135)
                
                //Verificar Si EL Usuario Está Confirmado Y Existe
                if($usuario && $usuario->confirmado === "1"){
                    //Generar Token
                    $usuario->crearToken();//(Ver Model/Usuario: Linea 106)
                    $usuario->guardar();//(Ver Active Record Linea 105)
                    //Enviar El Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();//(Ver Classes/Email: Linea 48)

                    //Alerta De Exito
                    Usuario::setAlerta('exito','Revisa Tu Email');
                }else{
                    Usuario::setAlerta('error','El Usuario No Existe O No Confirmado');
                }
            } //Fin If(empty($alertas))
        }
        //Obtener Alertas
        $alertas = Usuario::getAlertas();

        //Renderizado De Vista
        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router){
        //Inicializamos El Arreglo Para Las Alertas
        $alertas = [];
        //Obtenemos El Token De La URL, Si No Existe Sera Un String Vacio Para Evitar Undefined En La Vista
        $token = s($_GET['token'] ?? "");
        //Inicializamos Variable Error Que Nos Permitirá Para El Renderizado De La Vista En Caso De Errores
        $error = false;
        //Si No Se Obtiene Un Token (Por si acceden directamente a la URL sin token) No Renderizamos El Formulario
        if(!$token) {
            Usuario::setAlerta("error", "Token no valido");
            $error = true; //No Mostramos El Formulario (Ver Views/Auth/Recuperar-password Linea 5)
        }
        //Buscar Usuario Por Token
        $usuario = Usuario::where('token',$token);//(Ver Active Record Linea 135)
        if(empty($usuario)){
            Usuario::setAlerta('error','Token No Válido');
            $error = true; //No Mostramos El Formulario (Ver Views/Auth/Recuperar-password Linea 5)
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Leer El Nuevo Password Y Guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword(); //(Ver Model/Usuario: Linea 78 )

            if(empty($alertas)){
                //Eliminamos El Password Anterior
                $usuario->password = null;
                //De La Instancia De Usuario Tomo El Nuevo Password Y Se Lo Asigno Al Usuario
                $usuario->password = $password->password;
                //Hasheamos El Nuevo Password
                $usuario->hashPassword();//(Ver Model/Usuario: Linea 102)
                //Eliminamos El Token
                $usuario->token = null;
                //Actualizamos El Registro En La BD
                $resultado = $usuario->guardar();//(Ver Active Record Linea 105)
                //Redireccionamos Al Usuario
                if($resultado){
                    header('Location: /');
                }
                
            } //Fin if(empty($alertas))
        }

        //Obtener Alertas
        $alertas = Usuario::getAlertas();
        //Renderizar Vista
        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
//Hacemos la instancia fuera del if para poder llevarnos los datos a la vista y poder pre-llenar el formulario si 
//Hay algo incorrecto
        $usuario = new Usuario; //Creamos nueva instancia de usuario
        //Inicializamos El Arreglo Para Las Alertas
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Aqui Podemos Realizar Todo Tipo De Validaciones
            //Sincrozinar (Ver Active Record Linea 95)
            $usuario->sincronizar($_POST); //Sincronizamos el objeto vacio con los datos nuevos que han llegado (Post)
            $alertas = $usuario->validarNuevaCuenta();//(Ver Model/Usuario: Linea 35)
            //Validar que alertas esté vacío
            if(empty($alertas)){
                //Verificar Que El Usuario No Esté Registrado, (Ver El Modelo De Usuario Linea 91)
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){ //Si Está Registrado
                    //No Instancío nuevamente ya que el arreglo es protected static
                    //Obtenemos Las Alertas
                    $alertas = Usuario::getAlertas();
                }else{ //No Está Registrado
                    //Hashear Password
                    $usuario->hashPassword();//(Ver Model/Usuario: Linea 102)
                    //Generar Token Único
                    $usuario->crearToken();//(Ver Model/Usuario: Linea 106)
                    //Enviar Email, (Ver Classes)
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();//(Ver Classes/Email: Linea 17)
                    //Crear El Usuario
                    $resultado = $usuario->guardar();//(Ver Active Record Linea 105)
                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            } //Fin if(empty($alertas))

        }
        //Renderizamos La Vista
        $router->render('auth/crear-cuenta',[
            'usuario'=> $usuario,//Y con esto ya tenemos el objeto (valores del post) en la vista disponible
             'alertas'=> $alertas
            
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar (Router $router){
        //Inicializamos El Arreglo Para Las Alertas
        $alertas = [];
        //Obtenemos El Token De La URL, Si No Existe Sera Un String Vacio Para Evitar Undefined En La Vista
        $token = s($_GET['token'] ?? "");
        //Inicializamos Variable Error Que Nos Permitirá Para El Renderizado De La Vista En Caso De Errores
        $error = false;
        if(!$token) {
            Usuario::setAlerta("error", "Token no valido");
            $error = true;
        }
        $usuario = Usuario::where('token', $token); //(Ver Active-Record Linea 135)
        if(empty($usuario)){
            //Mostrar Mensaje De Error
            Usuario::setAlerta('error', 'Token No Valido'); //(Ver Active-Record Linea 22)
            $error = true;
        }else{
            //Cambiar A Confirmado = 1 En La BD
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar(); //Como Ya Tenemos Un Id Actualizará El Registro (Ver Active-Record Linea 105 y 172)
            $usuario->setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }
        //Obtener Alertas
        $alertas = Usuario::getAlertas();
        //Renderizar Vista
        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
}