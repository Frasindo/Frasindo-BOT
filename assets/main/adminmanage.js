$(document).ready(function() {
  $.getScript(base_url+"assets/main/main.js")
  .done(function( script, textStatus ) {
    table_main = $("#main").DataTable({
      ajax:base_url+"api/adminread/datatable"
    })
    $("#main").on('click', 'tbody tr', function(event) {
      event.preventDefault();
      data = table_main.row(this).data();
      var dialog = bootbox.dialog({
        title: '',
        message: '<p><center><i class="fa fa-spin fa-spinner"></i> Loading...</center></p>'
      });
      dialog.init(function() {
        setTimeout(function() {
          form = [{
            label: "Email",
            name: "email",
            type: "text",
            value:data[1]
          }, {
            label: "Username",
            name: "username",
            type: "text",
            value:data[2]
          }, {
            label: "",
            name: "id_admin",
            type: "hidden",
            value:data[0]
          }, {
            label: "Password",
            name: "password",
            type: "password"
          }];
          button = {name:"Update",class:"warning",type:"submit"};
          html = builder(form,button,"update");
          dialog.find(".bootbox-body").html("<div class='row'><div class='col-md-12'>"+html+"</div></div>");
          dialog.find("#update").on('submit', function(event) {
            event.preventDefault();
            dform = $(this).serializeArray();
            up = post(base_url+"api/adminupdate",dform);
            if (up.status == 1) {
              table_main.ajax.reload();
              bootbox.hideAll();
              swal("Success","Success to Update","success");
            }else {
              swal("Error","Failed to Update","error");
            }
          });
        }, 2000);
      });
    });
    $("#add").on('click', function(event) {
      event.preventDefault();
      var dialog = bootbox.dialog({
        title: '',
        message: '<p><center><i class="fa fa-spin fa-spinner"></i> Loading...</center></p>'
      });
      dialog.init(function() {
        setTimeout(function() {
          form = [{
            label: "Email",
            name: "email",
            type: "text"
          }, {
            label: "Username",
            name: "username",
            type: "text"
          }, {
            label: "Password",
            name: "password",
            type: "password"
          }];
          button = {
            name: "Save",
            class: "success",
            type: "submit"
          };
          html = builder(form, button, "save");
          dialog.find(".bootbox-body").html("<div class='row'><div class='col-md-12'>" + html + "</div></div>");
          dialog.find("#save").on('submit', function(event) {
            event.preventDefault();
            dform = $(this).serializeArray();
            ins = post(base_url + "api/admininsert", dform);
            if (ins.status == 1) {
              bootbox.hideAll();
              table_main.ajax.reload();
              swal("Success", "Data Saved", "success");
            } else {
              swal("Opps", "Data Failed to Save", "error");
            }
          });
        }, 2000);
      });
    });
  })
  .fail(function( jqxhr, settings, exception ) {
    swal("Error","Failed to Get Main Scripts","error");
  });
});
