<?php 
    function toast($esCorrecto, $mensaje) {
        // Imprime el script que muestra el mensaje de toast
        $echo = '
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 11">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="img/Icon v2.png" class="rounded me-2 logoig" alt="Logo">
                    <strong class="me-auto">Notizen</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
                <div class="toast-body">
                    ' . $mensaje . '
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $(".toast").toast("show");
            });
        </script>';

        return $echo;
      }
?>  