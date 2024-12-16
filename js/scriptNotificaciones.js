function crearNot(i, nodo) {
    this.nodo=nodo;
    let notificaciones= document.getElementById(nodo);
    let hora=document.createElement('input') ;
    let dia=document.createElement('input') ;
    let etiqueta=document.createElement('label');
    etiqueta.htmlFor=`notif${i}`;
    etiqueta.innerText= "Nueva Notificación:";
    etiqueta.className=`pt-2`;
    etiqueta.classList.add('fw-bold');
    dia.type="date";
    dia.id=`fechaNotif${i}`;
    dia.name=`fechaNotif${i}`;
    dia.className=`fechaNotif`;
    dia.classList.add('form-control');
    dia.required=true;

    hora.className=`fechaNotif${i}`;
    hora.type="time";
    hora.id=`horaNotif${i}`;
    hora.name=`horaNotif${i}`;
    hora.className=`horaNotificaciones`;
    hora.classList.add('form-control');
    hora.required=true;

    i++;

    if (nodo=="seccionBoton") {
        notificaciones.prepend(etiqueta, dia, hora);
    }
    else{
        notificaciones.appendChild(etiqueta);
        notificaciones.appendChild(dia);
        notificaciones.appendChild(hora);
    }
    notificaciones.style.display="block";

    if (i>=5){
        if (nodo=="seccionBoton") {
            document.getElementById("btnNotificacionSeccion").disabled=true;
        }
        else{
            document.getElementById("btnNotificacion").disabled=true;
        }
    }
    else{
        document.getElementById("btnNotificacion").setAttribute("onClick", `crearNot(${i}, nodo)`);
    }
}


function modalNotificaciones(id_tarea){
    $.ajax({
        url: 'notificacionGeneral.php',
        method: 'POST',
        data: { id_tarea : id_tarea, opc: '1' },
        success: function(result) {
            $('#modalNotificaciones').html(result);
            window.location.href = '#modalNotificaciones';
        }
    });
}


function eliminarNotificacion(id_notificacion, id_materia, id_tarea){
    $.ajax({
        url: 'notificacionGeneral.php',
        method: 'POST',
        data: { id_notificacion : id_notificacion, opc: '2', id_materia: id_materia, id_tarea: id_tarea },
        success: function(result) {
            $('#modalNotificaciones').html(result);
            window.location.href = '#modalNotificaciones';
            actualizaNoti();
        }
    });

}

function agregarNotificacion(id_tarea, id_materia){
    let dia = document.getElementById('fechaNotif4').value;
    let hora=document.getElementById('horaNotif4').value;

    $.ajax({
        url: 'notificacionGeneral.php',
        method: 'POST',
        data: { opc: '3', id_tarea: id_tarea, hora:hora, dia:dia, id_materia:id_materia },
        success: function(result) {
            $('#modalNotificaciones').html(result);
            window.location.href = '#modalNotificaciones';
            actualizaNoti();
        }
    });

}

function actualizaNoti(){
    $.ajax({
        url: 'cargarNotificaciones.php',
        method: 'POST',
        data: {},
        success: function(result1) {
            $('#cargaNoti').html("");
            $('#cargaNoti').html(result1);
        }
    });
}