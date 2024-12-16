$(document).ready(function () {
    function searchVideo(q, maxResults) { //Aquí se construyen los videos buscados
      var data = {
        maxResults : maxResults,
        key : "AIzaSyDC_jd-OstzAllwbXq_aJRwC_L2CZ5Jxi8", //clave de API
        part : "snippet",
        q : q,
        type : "video"
      }
  
      $.getJSON("https://www.googleapis.com/youtube/v3/search", data, function (res) {
        $("#video-list .video").remove();
        $("#te").remove();
        $(res.items).each(function () {
          var thumbnail = this.snippet.thumbnails.high.url;
          var title = this.snippet.title;
          var description = this.snippet.description;
          var id = this.id.videoId;
          var video = $('<div class="video row" data-video-id="' + id + '"> <div class="thumbnail col-lg-5 col-md-5 col-sm-5 col-12"> <img src="' + thumbnail + '" alt="Thumbail"> </div><div class="video-info col-lg-7 col-md-7 col-sm-7 col-12"> <h3>' + title + '</h3> <div class="description"> <p>' + description + '</p></div></div></div>');
          $("#video-list").append(video);
        });

        //Después de buscar te pone algo random de esa busqueda -- VER SI SE QUEDA
        var randomIndex = Math.floor(Math.random() * res.items.length);
        var randomVideoId = res.items[randomIndex].id.videoId;

        // Reproducir automáticamente el video seleccionado
        var videoUrl = "https://www.youtube.com/embed/" + randomVideoId + "?autoplay=1";
        $("iframe").attr("src", videoUrl);
      });
    }
  
    $("#desplegar").on("click", function () {
      if($("#senal").text() == "△" && $("#description").is(":visible") == true) {
        $("#description").toggle();
        $("#senal").text("▽")
      } //Controlamos si está el contenedor que muestra los videos/descripción inicial está abierto o cerrado
      else if($("#senal").text() == "▽" && $("#description").is(":visible") == false) {
        $("#description").toggle();
        $("#senal").text("△");
      }
    });
  
    $("#Buscador").on("submit", function (e) {
      e.preventDefault();
      var q = $("#q").val();
      if (q == "") {
        $('#buscaA').modal('show'); //Modal que abre
      }
      else {
        searchVideo(q, 5); //Genera los videos según la busqueda hecha
        $("#q").val("");
      }
      
      if($("#senal").text() == "▽" && $("#description").is(":visible") == false) {
        $("#description").toggle();
        $("#senal").text("△");
      } //Una vez que cargaron los videos de la busqueda desplegamos los resultados si el contenedor está oculto
    });
  
    $(document).on("click", ".video", function () { //Aquí se selecciona el video que eligió el usuario y se abre
      var urlBase = "https://www.youtube.com/embed/";
      var videoId = $(this).attr("data-video-id");
      var video = urlBase + videoId + "?autoplay=1";
      $("iframe").attr("src", video);
    });
  
  });
