<?php

namespace Model;

class Usuario extends ActiveRecord{
    //BD
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono',
    'admin', 'confirmado', 'token']; //Normaliza Los Datos o sea que itera sobre los registros y los inserta en el objeto que está en memoria
    //Atributos    
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    //Constructor
    public function __construct($args = []){ //Cuando se instancíe el objeto iremos agregando los argumentos con los atributos de nuestra clase
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //Mensajes De Validación Para Creación De Cuentas
    public function validarNuevaCuenta(){
        if(!$this->nombre){ //$this hace referencia al objeto que se está instanciando
            self::$alertas['error'][] = 'El Nombre Es Obligatorio';
        }

        if(!$this->apellido){
            self::$alertas['error'][] = 'El Apellido Es Obligatorio';
        }

        if(!$this->email){
            self::$alertas['error'][] = 'El Email Es Obligatorio';
        }

        if(!$this->password){
            self::$alertas['error'][] = 'El Password Es Obligatorio';
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El Password Debe Contener Al Menos 6 Caracteres';
        }

        return self::$alertas;
    }

    public function validarLogin() {
        if(!$this->email){
            self::$alertas['error'][] = 'El Email Es Obligatorio';
        }

        if(!$this->password){
            self::$alertas['error'][] = 'El Password Es Obligatorio';
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El Email Es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password){
            self::$alertas['error'][] = 'El Password Es Obligatorio';
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El Password Debe Tener Más De 6 Caracteres';

        }
        return self::$alertas;
    }

    //Revisa Si El Usuario Ya Existe
    public function existeUsuario(){
        //En esta instancia, el usuario ya está en memoria, por lo cual el email será el que él ha proporcionado
        //Desde Crear Cuenta
         $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";  //Comillas Simple Ya Que Es Un String
         $resultado = self::$db->query($query);
         if($resultado->num_rows){
            self::$alertas['error'][] = 'El Usuario Ya Está Registrado';
         }
         return $resultado;
    }

    public function hashPassword(){
        $this->password= password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = 'Password Incorrecto O Tu Cuenta No Ha Sido Confirmada';
        }else{
            return true;
        }
    }
    
}