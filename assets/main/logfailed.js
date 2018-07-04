$(document).ready(function() {
  $.getScript(base_url+"assets/main/main.js")
  .done(function( script, textStatus ) {
    table_main = $("#main").DataTable({
      ajax:base_url+"api/logfailedread/datatable"
    })
  })
  .fail(function( jqxhr, settings, exception ) {
    swal("Error","Failed to Get Main Scripts","error");
  });
});
