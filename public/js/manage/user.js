/**
Custom module for you to write your own javascript functions
**/
var User = function () {

    var callBackDeleteData = function (res) {

        if ( res.meta.success ) {

            var id  = res.response.id;
            // var row = $('#dataTable').find('tr[data-id=' + id + ']').remove();
            
            $('#modal-delete').modal('hide');
            $("#content-data").load(" #data-table");
        }
    };

    var callBackInsertData = function (res) {

        $("#content-data").load(" #data-table");
    };

    var callBackUpdateData = function (res) {

        $("#content-data").load(" #data-table");
    };

    // public functions
    return {

        //main function

        init: function () {

            $(document).ready(function(){

                var form_add_new    = $('#form-add-user');
                var form_edit       = $('#form-edit-user');

                var rules = {

                    name: {
                        required: true,
                        maxlength: 100,
                    },
                    address: {
                        required: true,
                        maxlength: 300,
                    },
                    age: {
                        required: true,
                        number: true,
                        maxlength: 2,
                    },
                };

                var message = {

                    name: {
                        required: 'Please enter name',
                        maxlength: 'Please enter no more than 100 characters',
                    },
                    address: {
                        required: 'Please enter address',
                        maxlength: 'Please enter no more than 300 characters',  
                    },
                    age: {
                        required: 'Please enter age',
                        number: 'Please enter number',
                        maxlength: 'Please enter no more than 2 digits',
                    },
                };

                form_add_new.validate({
                    rules,
                    message
                });

                form_edit.validate({
                    rules,
                    message
                });

                $(document).on('click', '.btn-delete', function() {

                    var id  = $(this).closest('tr').attr('data-id');

                    $('#modal-delete').find('input[name=id]').val( id );
                    $('#modal-delete').modal('show');
                });

                $(document).on('click', '.btn-submit-delete-user', function(e) {

                    e.preventDefault();
                    
                    var id  = $(this).closest('form').find('input[name=id]').val();
                    var url = $(this).closest('form').attr('data-url');

                    var data = {

                        id: id,
                    };

                    ajax_default(url, data, callBackDeleteData);
                });

                $('.btn-submit-add-user').on('click', function(e) {

                    e.preventDefault();

                    if ( form_add_new.valid() ) {

                        e.preventDefault();

                        var url         = $('#route-add-user').val();
                        var form        = $('#form-add-user').get(0); 
                        var formData    = new FormData( form );

                        $.ajax({
                            type:'POST',
                            url:            url,
                            data:           formData,
                            cache:          false,
                            contentType:    false,
                            processData:    false,

                            success:function(res){
                                
                                callBackInsertData(res);
                            },
                            error: function(res){

                                console.log("error");
                                console.log(res);
                            }
                        });
                    } else {
                        console.log('form not valid');
                    }
                });
                
                $(document).on('click', '.btn-edit', function() {
                    var id      = $(this).closest('tr').attr('data-id');
                    var name    = $(this).closest('tr').attr('data-name');
                    var address = $(this).closest('tr').attr('data-address');
                    var age     = $(this).closest('tr').attr('data-age');

                    form_edit.find('input[name=id]').first().val( id );
                    form_edit.find('input[name=name]').first().val( name );
                    form_edit.find('textarea[name=address]').first().val( address );
                    form_edit.find('input[name=age]').first().val( age );
                });

                $('.btn-submit-edit-user').on('click', function(e) {

                    e.preventDefault();

                    if ( form_edit.valid() ) {

                        e.preventDefault();

                        var url         = $('#route-edit-user').val();
                        var form        = $('#form-edit-user').get(0); 
                        var formData    = new FormData( form );

                        $.ajax({
                            type:'POST',
                            url:            url,
                            data:           formData,
                            cache:          false,
                            contentType:    false,
                            processData:    false,

                            success:function(res){
                                
                                callBackUpdateData(res);
                            },
                            error: function(res){

                                console.log("error");
                                console.log(res);
                            }
                        });
                    } else {
                        console.log('form not valid');
                    }

                });

            });
        },
    };

}();

/***
Usage
***/
//Custom.init();
//Custom.doSomeStuff();