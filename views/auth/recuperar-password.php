<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca Tu Nuevo Password A Continuación</p>
<?php include_once __DIR__ . "/../templates/alertas.php";?>

<?php if($error) return; ?>
<form action="" method="POST" class="formulario"> <!--El Action será el de esta misma URL pero no se pone ya que tenemos el Token en la URL y así se perdería-->
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu Nueva Contraseña">
    </div>
    <input type="submit" class="boton" value="Guardar Nueva Contraseña">
</form>

<div class="acciones">
    <a href="/">¿Ya Tienes Una Cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿No Tienes Cuenta? Crea Una</a>
</div>