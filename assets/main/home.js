$(document).ready(function() {
  $.getScript(base_url + "assets/main/main.js")
    .done(function(script, textStatus) {

      $("#runbot").on('click', function(event) {
        event.preventDefault();
        swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          buttons: [
            'No, cancel it!',
            'Yes, I am sure!'
          ]

        }).then(function (result) {
          if (result) {
            run  = get(base_url+"api/run");
            if (run.status == 1) {
              swal("Success","Yeaay Bot Has Been Running <br> Please Check Report Bot to See Log :D ","success");
            }else {
              swal("Error",run.msg,"error");
            }
          }else {
            swal("Info","Running Bor Canceled","info");
          }
        })
      });
    })
    .fail(function(jqxhr, settings, exception) {
      swal("Error", "Failed to Get Main Scripts", "error");
    });
});
