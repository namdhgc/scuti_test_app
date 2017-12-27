/**
Custom module for you to write your own javascript functions
**/
var User = function () {

    var elem_block_loadding;
    var elm_active;
    var url = '';

    var callBackDeleteData = function (res) {

        console.log( res );

        if ( res.meta.success ) {

            var id = res.response.id;

            var row  = $('#dataTable').find('tr[data-id=' + id + ']').remove();
            $('#modal-delete').modal('hide');
            
            // $('#data_table').load( this.href + ' #data_table');
            // $('#data_table').load( url + ' #data_table');
        }
    };

    // public functions
    return {

        //main function

        init: function () {

            $(document).ready(function(){

                var form_add_new    = $('#form-add-new');
                var form_edit       = $('#form-edit');

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

                $('.btn-delete').on('click', function() {

                    var id  = $(this).closest('tr').attr('data-id');

                    $('#modal-delete').find('input[name=id]').val( id );
                    $('#modal-delete').modal('show');
                });

                $('.btn-submit').on('click', function(e) {

                    e.preventDefault();
                    
                    var index_url   = $('#route-home-page').val();
                    var id          = $(this).closest('form').find('input[name=id]').val();
                    url             = $(this).closest('form').attr('data-url');

                    var data = {

                        id: id,
                    };

                    ajax_default(url, data, callBackDeleteData);
                    // $('#dataTable').load( this.href + ' #dataTable');
                    // $('#dataTable').load( index_url + '#dataTable' );
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