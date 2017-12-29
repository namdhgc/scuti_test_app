/**
Custom module for you to write your own javascript functions
**/
var User = function () {

    var elem_block_loadding = 'body';

    var refreshData = function() {
        var sort            = $('#sort_data');
        var sort_by         = sort.find(':selected').attr('data-sort-by');
        var sort_type       = sort.find(':selected').attr('data-sort-type');
        var url             = $('#route-home-page').val();
        var current_page    = $('#current-page').val();
        url                 = url + '/?page=' + current_page;
        if ( sort_by != '' && sort_type != '' ) {
            url = url + '&sort_by=' + sort_by + '&sort_type=' + sort_type;
        }
        $("#content-data").load(url + ' #data-table', function(){
            unblockUI();
        });
    };

    var callBackDeleteData = function (res) {
        $('#modal-delete').modal('hide');
        refreshData();
    };

    var callBackInsertData = function(res) {
        refreshData();
    };

    var callBackUpdateData = function(res) {
        refreshData();
    };

    var callBackSortPagination = function(res) {
        refreshData();
    };

    var blockUI = function() {
        App.blockUI({
            target: elem_block_loadding,
            boxed: true,
            zIndex: 11000,
        });
    };

    var unblockUI = function() {
        App.unblockUI(elem_block_loadding);
    };

    var ajaxDefaultSendImage = function(url, formData, callBack) {
        $.ajax({
            type:'POST',
            url:            url,
            data:           formData,
            cache:          false,
            contentType:    false,
            processData:    false,

            success:function(res){
                callBack(res);
            },
            error: function(res){
                // do something when error
                console.log("error");
                console.log(res);
            }
        });
    };

    // public functions
    return {
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
                    blockUI();
                    var id      = $(this).closest('form').find('input[name=id]').val();
                    var url     = $(this).closest('form').attr('data-url');
                    var data    = {
                        id: id,
                    };
                    ajax_default(url, data, callBackDeleteData);
                });

                $('.btn-submit-add-user').on('click', function(e) {
                    e.preventDefault();
                    blockUI();
                    if ( form_add_new.valid() ) {
                        e.preventDefault();
                        var url         = $('#route-add-user').val();
                        var form        = $('#form-add-user').get(0); 
                        var formData    = new FormData( form );

                        ajaxDefaultSendImage( url, formData, callBackInsertData );
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
                    blockUI();
                    if ( form_edit.valid() ) {
                        e.preventDefault();
                        var url         = $('#route-edit-user').val();
                        var form        = $('#form-edit-user').get(0); 
                        var formData    = new FormData( form );

                        ajaxDefaultSendImage( url, formData, callBackUpdateData );
                    } else {
                        console.log('form not valid');
                    }
                });

                $(document).on('click', '.pagination-link', function(e) {
                    e.preventDefault();
                    var url             = $('#route-sort-pagination').val();
                    var selected_page   = $(this).attr('data-page');
                    var current_page    = $('#current-page');
                    var sort            = $('#sort_data');
                    var sort_by         = sort.find(':selected').attr('data-sort-by');
                    var sort_type       = sort.find(':selected').attr('data-sort-type');

                    $('.pagination li.' + current_page.val()).attr('class', current_page.val());
                    $('.pagination li.' + selected_page).attr('class', selected_page + ' active');
                    current_page.val( selected_page );

                    data = {
                        selected_page   : selected_page,
                        sort_by         : sort_by,
                        sort_type       : sort_type,
                    };
                    
                    blockUI();
                    ajax_default(url, data, callBackSortPagination);
                });

                $('#sort_data').on('change', function(e) {
                    e.preventDefault();
                    var url             = $('#route-sort-pagination').val();
                    var current_page    = $('#current-page');
                    var sort            = $('#sort_data');
                    var sort_by         = sort.find(':selected').attr('data-sort-by');
                    var sort_type       = sort.find(':selected').attr('data-sort-type');

                    data = {
                        selected_page   : current_page.val(),
                        sort_by         : sort_by,
                        sort_type       : sort_type,
                    };

                    blockUI();
                    ajax_default(url, data, callBackSortPagination);
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