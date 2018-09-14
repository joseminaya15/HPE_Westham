function ingresar(){
  var usuario  = $('#usuario').val();
  var password = $('#password').val();
  if(usuario == null){
    msj('error', 'Ingrese un usuario');
    return;
  }
  if(password == null){
    msj('error', 'Ingrese un usuario');
    return;
  }
  $.ajax({
    data : {usuario : usuario,
            password : password},
    url  : 'login/ingresar',
    type : 'POST'
  }).done(function(data){
    try{
        data = JSON.parse(data);
        if(data.error == 0){
          location.href = 'admin';
          $('#usuario').val("");
          $('#password').val("");
        }else {
          msj('error', data.msj);
          return;
        }
      }catch(err){
        msj('error',err.message);
      }
  });
}
function cerrarCesion(){
  $.ajax({
    url  : 'admin/cerrarCesion',
    type : 'POST'
  }).done(function(data){
    try{
        data = JSON.parse(data);
        if(data.error == 0){
          location.href = 'Login';
        }else {
          return;
        }
      }catch(err){
        msj('error',err.message);
      }
  });
}
function verificarDatos(e){
  if(e.keyCode === 13){
    e.preventDefault();
    ingresar();
    }
}
function goToCityAdmin(){
  var city = $('#cityAdmin').val();
  $.ajax({
    data : {city:city},
    url  : 'admin/changeTableCity',
    type : 'POST'
  }).done(function(data){
    try{
        data = JSON.parse(data);
        if(data.error == 0){
          // table.destroy();
          $('#example').DataTable().destroy();
          $('#tablecity').html('');
          $('#tablecity').append(data.html);
          $('#example').DataTable( {
              searching : false,
              responsive: true,
              dom: 'Bfrtip',
              aLengthMenu : [100],
              destroy : true,
              buttons: [
                  {
                      extend:'excel',
                      text: 'Exportar a Excel'
                  },
                  {
                      extend:'print',
                      text: 'Imprimir'
                  }
              ],
              language : {
                  info : "Mostrando _TOTAL_ registros",
              }
          });
        }else {
          return;
        }
      }catch(err){
        msj('error',err.message);
      }
  });
}