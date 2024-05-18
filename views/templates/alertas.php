<?php
//El 1er foreach itera sobre el arreglo principal para acceder al $Key
//El 2do foreach accederá a los mensajes
foreach($alertas as $key => $mensajes):
    foreach($mensajes as $mensaje):
?>
<!--No sanitizamos los datos ya que estos están en el modelo, los datos que se sanitizan son los datos que ingresan los usuarios en el formulario-->
    <div class="alerta <?php echo $key ?> ">
        <?php echo $mensaje; ?>
    </div>
<?php
    endforeach;
endforeach;
?>