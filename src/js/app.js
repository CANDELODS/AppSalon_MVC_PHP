let paso = 1;
//Calculo Para Los Resultado
const pasoInicial = 1;
const pasoFinal = 3;
//Objeto Con La Información De La Cita
const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}
//Inicializamos El Proyecto: Cuando Todo El DOM Esté Cargado Entonces
document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
});

function iniciarApp() { //Esta Función Cargará Todas Las Funciones Que Se Tendrán
    mostrarSeccion(); //Selecciona La Sección Y La Muestra O La Oculta, Ademas Mostrará Automáticamente La Primera
    tabs(); //Cambia La Sección Cuando Se Presionen Los Tabs
    botonesPaginador(); //Agrega O Quita Los Botones Del Paginador
    paginaSiguiente(); //Funcionalidad Boton "Siguiente"
    paginaAnterior(); //Funcionalidad Boton "Anterior"

    consultarAPI(); //Consultar La API En El Backend De PHP
    idCliente(); //
    nombreCliente(); //Añade El Nombre Del Cliente Al Objeto De Cita
    seleccionarFecha(); // Añade La Fecha De La Cita Al Objeto De Cita
    seleccionarHora(); // Añade La Hora De La Cita Al Objeto De Cita

    mostrarResumen() //Muestr El Resumen De La Cita
}

function mostrarSeccion() {
    //Ocultar La Sección Que Tenga La Clase Mostrar
    const seccionAnterior = document.querySelector('.mostrar'); //Seleccionamos La Que Tiene La Clase Mostrar
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar'); //Le Quitamos La Clase Mostrar   
    }

    //Seleccionar La Sección Con El Paso...
    const pasoSelector = `#paso-${paso}` //Query String  Donde Obtenemos data-paso Y Le Damos El Valor Iterado En Tabs
    const seccion = document.querySelector(pasoSelector);
    //Asignamos La Clase Mostrar, La Cual Mostrará El Contenido
    seccion.classList.add('mostrar');

    //Quita La Clase De Actual Al Tab Anterior
    const tabAnteorior = document.querySelector('.actual');
    if (tabAnteorior) {
        tabAnteorior.classList.remove('actual');
    }
    //Resalta El Tab Actual
    const tab = document.querySelector(`[data-paso="${paso}"]`); //Al Seleccionar Atributos Con Un Query String Usamos [Atributos] En Vez De #(Id)
    tab.classList.add('actual');
}

function tabs() {
    //Seleccionamos Todos Los Botones Con La Clase .tabs, Y Que Tengan La Etiqueta Button
    const botones = document.querySelectorAll('.tabs button');
    //Como No Podemos Usar AddEventListener Ya Que Estamos Usando querySelectorAll Entonces
    //Vamos A Iterar Sobre Todos Los Resultados (Botones) E Ir Asociando El Evento
    botones.forEach(boton => {
        boton.addEventListener('click', function (e) { //El evento e Nos Permitirá Acceder A Diversas Propiedades Para Diferenciar Los Botones
            //Con dataset Accedemos A Los Atributos Que Creamos, En Este Caso El (data-paso) Que Está En Los Botones
            paso = parseInt(e.target.dataset.paso); //Asociamos El Valor Que Tenemos En El Atributo Personalizado
            mostrarSeccion();
            botonesPaginador();
        });
    })

}

function botonesPaginador() {
    //Seleccionamos Los Botones
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if (paso === 1) {
        paginaAnterior.classList.add('ocultar'); //Ocultamos El Boton "Anterior"
        paginaSiguiente.classList.remove('ocultar');//Quitamos La Clase Para Que Al Volver A Esa Sección Se Muestre El Boton
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar'); //Mostramos El Boton "Anterior"
        paginaSiguiente.classList.add('ocultar'); //Ocultamos El Boton "Siguiente"
        mostrarResumen(); //Mostramos El Resumen De La Información
    } else {
        paginaAnterior.classList.remove('ocultar'); //Mostramos El Boton "Anterior"
        paginaSiguiente.classList.remove('ocultar');//Mostramos El Boton "Siguiente"
    }

    mostrarSeccion(); //Mostramos La Debida Sección
}

function paginaAnterior() {
    //Seleccionamos El Boton "Anterior"
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function () {
        if (paso <= pasoInicial) return; //pasoInicial=1
        //De Lo Contrario
        paso--;
        botonesPaginador();
    });
}

function paginaSiguiente() {
    //Seleccionamos El Boton "Siguiente"
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function () {
        if (paso >= pasoFinal) return; //pasoFinal=3
        //De Lo Contrario
        paso++;
        botonesPaginador(); //Ocultamos O Mostramos Los Botones
    });
}

async function consultarAPI() {
    //Con El Try Catch Intentamos Hacer La Conexión A La API, Si No Se Puede Nos Mostrará El Error
    //Además Prevendrá Que Mi Aplicación Deje De Funcionar
    try {
        const url = '/api/servicios'; //URL De Mi API
        //Con El Await Esperamos A Que Se Descargue Todo Lo Que Necesitamos De La API
        const resultado = await fetch(url); //Función Que Nos Permitirá Consumir El Servicio
        const servicios = await resultado.json(); //Obtenemos Los Resultados Como Json
        mostrarServicios(servicios);
    } catch (error) {

    }
}

function mostrarServicios(servicios) {
    //Iterar Los Servicios
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio; //Creamos Variable y Extraemos El Valor
        //Scripting (Nos Permitirá Un Mayor Perfomance)
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id; //Atributo Personalizado
        //Pasar Datos De Una Función A Otra Usando Scripting
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio); //Creamos Un Nodo Hijo O Sea Metemos El P En El DIV
        servicioDiv.appendChild(precioServicio);

        //El Div Que Tenemos En La Vista (Views/cita/index.php) Le Inyectamos Todo Lo Que Tiene El DIV
        //servicioDiv(nombreServicio, precioServicio)
        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

function seleccionarServicio(servicio) {
    //Extraemos El Id Del Servicio
    const { id } = servicio;
    //Escribir El Objeto En El Arreglo De Servicios
    const { servicios } = cita; //Extraigo Servicios Del Arreglo De Citas

    //IDENTIFICAR EL ELEMENTO AL QUE SE LE DA CLICK
    //Seleccionamos El Atributo Y Le Pasamos El Id Para Reconocerlo
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar Si Un Servicio Fue Agregado
    if (servicios.some(agregado => agregado.id === id)) { //Retorna True o False Si Un Elemento Ya Existe En El Arreglo
        //ELIMINAR
        //Con FIlter Sacamos Un Elemento Dependiendo De Una Condición
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        //Quitamos Clase Y Cambiamos Apariencia
        divServicio.classList.remove('seleccionado');
    } else {
        //AGREGAR
        //Tomo Una Copia Del Arreglo Servicios y Lo Agrego Al Nuevo Objeto
        //Esto Me Creará Un Nuevo Arreglo Con La Info Nueva Y La Reescribe
        cita.servicios = [...servicios, servicio];
        //Agregamos Clase Y Cambiamos Apariencia
        divServicio.classList.add('seleccionado');
    }

}

function idCliente(){
    const id = document.querySelector('#id').value;
    //Asignamo El Valor Al Objeto Cita
    cita.id = id;
}

function nombreCliente() {
    const nombre = document.querySelector('#nombre').value;
    //Asignamo El Valor Al Objeto Cita
    cita.nombre = nombre;
}

function seleccionarFecha() {
    const InputFecha = document.querySelector('#fecha');
    InputFecha.addEventListener('click', function (e) {

        const dia = new Date(e.target.value).getUTCDay();
        //Con Includes Comprobamos Si Un Valor Existe, A Diferencia De Some, Includes No Necesita Callback
        if ([6, 0].includes(dia)) { //El 6 y el 0 Hacen Referencia A Los Dias Sabado Y Domiendo Del Objeto Date.getUTCDay
            e.target.value = '';
            mostrarAlerta('Fines De Semana No Permitidos', 'error', '.formulario');
        } else {
            //Si Es Correcto Entonces Agregamos El Valor De La Fecha Al Objeto Cita
            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function (e) {
        const horaCita = e.target.value;
        //Split Nos Permite Separar Una Cadena De Texto, Esto Nos Retorna Un Arreglo, Al Cual Accederemos A Su
        //Primera Posición La Cual Contiene Solo La Hora
        const hora = horaCita.split(":")[0];
        if (hora < 10 || hora > 18) {
            e.target.value = ''; //Quitamos La Hora Ya Que Está Mal Puesta
            mostrarAlerta('Hora No Válida', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;
            // console.log(cita);
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    //Prevenir Que Se Cree Mas De Una Alerta
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    };

    //Scripting Para Crear La Alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    const referencia = document.querySelector(elemento); //Tambien Podría Ponerlo En (#paso-2 p)
    referencia.appendChild(alerta);

    if (desaparece) {
        //Eliminamos La Alerta Despues De 3 Segundos
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

}


function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');
    //Limpiar El Contenido De Resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }
    //Validar Que La Información (El Objeto De Cita) Esté Completa
    //Values Nos Sirve Para Acceder A Todos Los Valores Del Objeto
    if (Object.values(cita).includes('') || cita.servicios.length === 0) { //Iteramos Objeto Cita Y Verificamos Si Hay Un String Vacio O Si No Se Ha Seleccionado Un Servicio
        mostrarAlerta('Faltan Datos De Servicios, Fecha U Hora', 'error', '.contenido-resumen', false);
        return; //Detenemos La Ejecución Del Código Para No Tener Todo El Resto En Un Else
    }

    //SCRIPTING
    const { nombre, fecha, hora, servicios } = cita;


    //Heading Para Servicios En Resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen De Servicios';
    resumen.appendChild(headingServicios);

    //Iterar Y Mostrar Servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    //Heading Para Cita En Resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen De Cita';
    resumen.appendChild(headingCita);

    //Formatear El Div De Resumen
    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //Formatear La Fecha En Español
    //"Cada Que Se Instancía El Objeto De Fecha Se Tiene Un Desfase De Un Día"
    //Como Instanciamos Dos Veces, Le Sumamos 2 Al Día (Linea 304)
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2; //getDay Retorna El Día De La Semana, getDate El Día Del Mes
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia));

    //Regresar Fecha Formateada En Un Lenguaje Específico (toLocaleDateString)
    const opciones = { weekDay: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('es-CO', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

    //Boton Para Crear Una Cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita; 

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}

async function reservarCita(){
    //Enviar Datos Desde JS A PHP
    const {nombre, fecha, hora, servicios, id} = cita;
    //En La Tabla Donde Vamos A Guardar La Info Solo Necesitamos El id Del Servicio, El Resto Ya Está En La DB
    //El Map Me Permite Extraer Las Coincidencias En Una Variable,
    //Entonces Itero Sobre Cada Servicio, Identifico El Id Y Lo Paso A La Variable
    const idServicios = servicios.map(servicio => servicio.id) 

    const datos = new FormData(); //El Submit De JS
    datos.append('fecha', fecha); //Con El Primer Parámetro Es Con El Que Podemo Acceder Al Post (APIController Linea 16)
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    try {
        //Petición Hacia La API
    const url = '/api/citas'; 

    //Conectamos El Archivo De JS Con El Controlador Definido Por Medio De La URL
    const respuesta = await fetch(url, { //El Segundo Parametro Es Un Objeto, Para Un Post Es Obligatorio
        method: 'POST',
        body: datos   //Le Decimos A Fetch Donde Está La Información, Para Que Pueda Enviarla A La URL Definida
    });
    	
    const resultado = await respuesta.json();
    console.log(resultado.resultado);

    //Alerta Cita Creada Por Medio De SweetAlerts2 (Ver Views/Cita/index.php Linea 52)
    if(resultado.resultado){
        Swal.fire({
            icon: "success",
            title: "Cita Creada",
            text: "Tu Cita Fue Creada Correctamente",
            button: 'OK'
          }).then( () =>{
            window.location.reload(); //Recargamos La Página
          })
    }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo Un Error Al Guardar La Cita"
          }); 
    }
    
    
    // console.log([...datos]);
}