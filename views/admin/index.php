<h1 class="nombre-pagina">Panel De Administración</h1>
<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php
    if(count($citas) === 0){
        echo "<h2>No Hay Citas Para Esta Fecha</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php
        $idCita = 0; //Para Que No Muestre Undefined
        foreach ($citas as $key=> $cita) { //Con El $key Obtenemos La Posición En El Arreglo Del Registro, Esto Para Poder Mostrar El Total
            if ($idCita !== $cita->id) { //No Repetemos Datos
                $total = 0;
        ?>

                <li>
                    <p>ID: <span> <?php echo $cita->id ?> </span> </p>
                    <p>Hora: <span> <?php echo $cita->hora ?> </span> </p>
                    <p>Cliente: <span> <?php echo $cita->cliente ?> </span> </p>
                    <p>Email: <span> <?php echo $cita->email ?> </span> </p>
                    <p>Telefono: <span> <?php echo $cita->telefono ?> </span> </p>
                    <h3>Servicios</h3>
                <?php
                $idCita = $cita->id; //Tomará El Valor De La Primera Iteracción Y Lo Comparará Al Iniciar Otra Vez
            } //Fin If 
                $total += $cita->precio;
                ?>
                <p class="servicio"> <?php echo $cita->servicio . " " . $cita->precio; ?> </p>
            	<!--Aquí Termina El Li, Pero Creaba Mucho Margin, Así Que Dejamos Que Se Cierre Automaticamente-->

                <?php
                    $actual = $cita->id; //$cita = Objeto Que Estamos Iterando (Obtenemos El Id En El Cual Nos Encontramos)
                    //Identificamos Cual Es El Ultimo Registro Que Tiene El Mismo Id Para Identificar El Ultimo
                    $proximo = $citas[$key + 1]->id ?? 0; //$citas = Global O Sea El Resultado De La Consulta De La BD (Obtenemos El Indice En El Arreglo De La BD)
                    if(esUltimo($actual, $proximo)){ ?>
                        <p class="total">Total: <span>$ <?php echo $total; ?></span> </p>

                        <form action="/api/eliminar" method="POST" onsubmit="return confirmDelete()">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                        </form>
                        <script> function confirmDelete() {
                            return confirm("¿Estás seguro de que deseas eliminar este registro/cita?"); 
                        } </script>
                <?php } 
            } //Fin ForEach ?>
    </ul>

</div>

<?php
    $script = "
    <script src='build/js/buscador.js'></script>
    ";
?>