$(document).ready(function() {
    // Mostrar y ocultar las notificaciones cuando se haga clic en el botón
    $('#notificacion').click(function() {
      // Si las notificaciones están ocultas, mostrarlas
      if ($('#notificaciones').is(':hidden')) {
        // Ocultar el numero
        $('.num').hide();
        // Mostrar las notificaciones
        $('#notificaciones').show();
      } 
      //Si las notificaciones están visibles, ocultarlas
      else {
        // Mostrar el numero
        $('.num').show();
        // Ocultar las notificaciones
        $('#notificaciones').hide();
      }
    });
  
    // Ocultar las notificaciones cuando se hace clic en cualquier lugar fuera de las notificaciones
    $(document).click(function(event) {
      // Si el elemento clickeado no es la notificación ni el botón que la abre, ocultar las notificaciones
      if (!$(event.target).closest('.dropdown').length) {
        // Mostrar el numero
        $('.num').show();
        // Ocultar las notificaciones
        $('#notificaciones').hide();
      }
    });
  });

  function notificacionPush(nombreTarea, cuerpo) {
      if (Notification.permission === 'granted') {
          var notification = new Notification(nombreTarea, {body:cuerpo});
          notification.onclick = function() {
          window.location.href = "tareadetalle.php";
          };
      }
      else{
          Notification.requestPermission().then((result)=>{
              console.log(result);
          });
      }
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