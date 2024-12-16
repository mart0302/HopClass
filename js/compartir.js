$(document).ready(function () {
$('#compartir').change(function (e) {
  if ($(this).val() === "1") {
    $('#amigos').prop("disabled", false);
  } else {
    $('#amigos').prop("disabled", true);
  }
})
});
