document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha(){
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e){
        const fechaSeleccionada = e.target.value;
        window.location = `?fecha=${fechaSeleccionada}`;
    });
}

// function alertaEliminarCita(e){
//         e.preventDefault(); //Previene El Envio Del Formulario Inmediatamente
//         Swal.fire({
//             title: "Confirmación",
//             text: "¿Está Seguro De Eliminar La Cita?",
//             icon: "warning",
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Sí, Eliminar',
//             cancelButtonText: 'No, Cancelar',
//             padding: "4rem"
//         })
//         .then((result) => {if(result.isConfirmed) {document.querySelector('#formEliminar').submit();} });
// }

// function alertaEliminarServicios(e){
//     e.preventDefault(); //Previene El Envio Del Formulario Inmediatamente
//     Swal.fire({
//         title: "Confirmación",
//         text: "¿Está Seguro De Eliminar El Servicio?",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Sí, Eliminar',
//         cancelButtonText: 'No, Cancelar',
//         padding: "4rem"
//     })
//     .then((result) => {if(result.isConfirmed) {document.querySelector('#formEliminarServicios').submit();} });
// }
