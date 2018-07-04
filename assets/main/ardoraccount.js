$(document).ready(function() {
  $.getScript(base_url + "assets/main/main.js")
    .done(function(script, textStatus) {
      table_main = $("#main").DataTable({
        ajax: base_url + "api/ardorread/datatable"
      });
      $("#main").on('click', 'tbody tr', function(event) {
        event.preventDefault();
        data = table_main.row(this).data();
        var dialog = bootbox.dialog({
          title: '',
          message: '<p><center><i class="fa fa-spin fa-spinner"></i> Loading...</center></p>'
        });
        dialog.init(function() {
          setTimeout(function() {
            form = [
              {
                label:"Public Key",
                name:"publickey",
                type:"text",
                value:data[1]
              },{
                label : "",
                name:"id_ardor",
                value:data[0],
                type:"hidden"
              },{
                label:"Secret Key",
                name:"secretkey",
                type:"text"
              }
            ];
            button = {name:"Update",class:"warning",type:"submit"};
            html = builder(form,button,"update");
            dialog.find(".bootbox-body").html("<div class='row'><div class='col-md-12'>"+html+"</div></div>");
            dialog.find("#update").on('submit', function(event) {
              event.preventDefault();
              dform = $(this).serializeArray();
              up = post(base_url+"api/ardorupdate",dform);
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
              label: "Public Key",
              name: "publickey",
              type: "text"
            }, {
              label: "Secret Key",
              name: "secretkey",
              type: "text"
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
              ins = post(base_url + "api/ardorinsert", dform);
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
    .fail(function(jqxhr, settings, exception) {
      swal("Error", "Failed to Get Main Scripts", "error");
    });
});
