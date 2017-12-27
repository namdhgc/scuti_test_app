/**
Custom module for you to write your own javascript functions
**/

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})

var getMessage = function(msg) {

  var message = '';
  var i = 0;

  if( $.isArray(msg) || typeof(msg) == "object" ) {

    for( i in msg ) {

        message += getMessage( msg[i] ) + "<br>";
    }

  } else {
    
    message = msg + "<br>";
  }

  return message;
};

var show_message = function(res) {

  var type_message  = 'success';
  var show          = true;
  if( !res.meta.success ) type_message = 'error';
  if( res.meta.success && res.meta.msg.length == 0 ) show = false;

  if(show) {

    var msg = res.meta.msg;

    var message = getMessage( msg ) ;
    // toastr[type_message](message, "Notifications")
  }
};

var ajax_default = function(url, data, callBack) {

    $.ajax({
        method: "POST",
        url: url,
        dataType: 'json',
        data: data,
        success : function(res){

        if( res.meta != undefined ){
            show_message(res);
        }
            callBack(res);
        }
        ,error: function (jqXHR, exception) {

            var msg = '';

            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
        },
    });
}

/***
Usage
***/
//Custom.init();
//Custom.doSomeStuff();