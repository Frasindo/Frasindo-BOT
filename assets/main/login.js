$(document).ready(function() {
  $.getScript(base_url + "assets/main/main.js")
    .done(function(script, textStatus) {
      console.log("Login");
      $("#logmasuk").on('submit', function(event) {
        event.preventDefault();
        dform = $(this).serializeArray();
        login = post(base_url + "api/login", dform);
        if (login.status == 1) {
          swal({
            title: "Login Berhasil ",
            text: "Klik OK Untuk Melanjutkan",
            type: "success",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          }).then((res)=>{
            location.href = base_url+"admin";
          });
        } else {
          $(this)[0].reset();
          swal("Error", "Login Gagal", "error");
        }
      });
    })
    .fail(function(jqxhr, settings, exception) {
      swal("Error", "Failed to Get Main Scripts", "error");
    });
});
