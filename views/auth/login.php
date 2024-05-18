<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia Sesión Con Tus Datos</p>
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu Email O Correo Electronico" name="email"> <!--El name me permite tenerlo en la superglobal POST-->
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" placeholder="Tu Contraseña" name="password">
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿No Tienes Cuenta? Crea Una</a>
    <a href="/olvide">¿Olvidaste Tu Contraseña?</a>
</div>