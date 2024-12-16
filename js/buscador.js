  document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("buscador");
    var resultado = document.getElementById("resultado");

    form.addEventListener("submit", function(e) {
      e.preventDefault();
      var search = form.querySelector("input[name=search]").value;
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "amigos/buscar.php");
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function() {
        console.log("Estado de la solicitud:", xhr.readyState);
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          console.log("Respuesta del servidor:", xhr.responseText);
          resultado.innerHTML = xhr.responseText;
        }
      };

      xhr.send("search=" + search);
    });
  });