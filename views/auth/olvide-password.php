<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Reestablece Tu Contraseña Escribiendo Tu Email O Correo</p>
<?php include_once __DIR__ . "/../templates/alertas.php";?>

<form action="/olvide" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu Email" name="email">
    </div>

    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>

<div class="acciones">
    <a href="/">¿Ya Tienes Una Cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿No Tienes Cuenta? Crea Una</a>
</div>