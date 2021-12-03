const INSTAT_PAID = 3;

var full_url;

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function () {
    if ((window.location.hash).slice(1) != "")
        Routes();
});

//generalised markAsPaid modal
function deleteAJAX(identifier) {
    var url = $(identifier).data('url');
    var params = { id: $(identifier).attr('data-id'), inIdOrInIdCnId: $(identifier).attr('data-inIdOrInIdCnId'), ctAmount: $(identifier).attr('data-ctAmount'), isCreditNote: $("#isCreditNote").text() };
    AJAXcall(url, params);
}

/* Page Url */

var dashboard = "/dashboard/index";

//var itemUpdateUrl = "/items_ajax/update"; //create & update are now same
//var prodAddUrl = "/products_ajax/create";

//ajax call
// $(window).on('hashchange', function() {
//     // $('body').addClass('loader');
//     Routes();
// });

function Routes(url, params = []) {
    $('loader').removeClass('d-none');
    if (history.pushState) {
        history.pushState(null, null, url);
        params.push({ name: '_token', value: $('input[name=_token]').val() });
        AJAXcall(url,  params);
    }
    return false;
}
var popped = ('state' in window.history && window.history.state != null), initialURL = location.href;


$(window).bind('popstate', function (event) {
    var initialPop = !popped && location.href == initialURL
    popped = true
    if (initialPop) { return } else { AJAXcall(window.location.href.replace(base_url, '')) };
});
var obj;
function AJAXcall(url, params = false, otherParams = false) {
    full_url = base_url + url;

    $.post(full_url, params)
        .done(function (data) {
            // obj = jQuery.parseJSON(data);
            console.log(data);
            ajaxDone(data, url, params, otherParams);
        })
        .fail(function (data) {
            // var obj = jQuery.parseJSON(data);
            //window.location.href = base_url+'/dashboard';
        })
}

function ajaxDone(obj, url, params, otherParams) {
    $.each(obj, function (key, value) {
        switch (key) {
            case 'show_notify':
                new PNotify(value);
                break;

            case 'message':
                $("#inner-notification").append(obj.message);
                break;

            case 'css_html':
                $('#css-html').html("").append(obj.css_html);
                break;

            case 'header_html':
                $('#header-html').html("").append(obj.header_html);
                break;

            case 'sidebar_html':
                $('#sidebar-html').html("").append(obj.sidebar_html);
                break;

            case 'topbar_html':
                $('#topbar-html').html("").append(obj.topbar_html);
                break;

            case 'view_html':
                $('#view-html').html("").append(obj.view_html);
                break;

            case 'footer_html':
                $('#footer-html').html("").append(obj.footer_html);
                break;

            case 'modal_data':
                var modal = $(obj.modal_data.modal_id).modal('show');

                modal.find(obj.modal_data.modal_id + '-title').text(obj.modal_data.title);
                modal.find('.modal-body').html(obj.modal_data.body_html);
                (obj.modal_data.footer_html) ? modal.find('.modal-footer').html(obj.modal_data.footer_html) : modal.find('modal-footer').css('display', 'none');
                break;

            case 'update':
                $(obj.update.selector).html("").append(obj.update.selector_html);
                break;

            case 'eval':
                eval(obj.eval);
                break;

            case 'redirect':
                window.location = base_url + obj.redirect;
                break;

            case 'url':
                Routes(obj.url);
                break;

            case 'js_html':
                $('#js-html').html("").append(obj.js_html);
                break;

            default:
                break;
        }

    });


    console.log(obj);
    if (otherParams && otherParams.buttonId) {
        otherParams.buttonId.button('reset');
    }

    // $('body').removeClass('loader');
    $('#loader').addClass('d-none');
    // setTimeout(function () {

    // }, 500);
}

/*
Create Rceipt
*/
function createItemFromRcpt(rcpt) {
    var post_params = { itTitle: rcpt.rtTitle, itPrice: rcpt.rAmount, txId: 1, inId: rcpt.inId, itId: 0, rId: rcpt.rId, itQty: 1, itDesc: rcpt.rCmts };
    AJAXcall(itemCreateUrl, post_params);
}

$(window).on('load', function () {
    $('#loader').addClass('d-none');
});

/*
Copy Address Fields
*/

var params = [];
// Load New Page with params
$(document).on('click', '.loadPage', function (e) {
    $('#loader').removeClass('d-none');
    var url = $(this).attr('data-url');
    url = url.replace(base_url, "");
    
    params.push({ name: '_token', value: $('input[name=_token]').val() });
    Routes(url, params);
});

$(document).on('click', 'button.sendVerifyCode, .sendVerifyCode', function () {
    var post_params = [];
    if ($(this).attr('data-form-id'))
        post_params = $('#' + $(this).attr('data-form-id')).serializeArray();
    else
        post_params.push({ name: 'editField', value: $(this).attr('data-edit-field-name') })

    var conrim_msg = "You won't be able to change this!";
    var url = $(this).attr('data-url');
    swal({
        title: 'Are you sure?',
        text: conrim_msg,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger m-l-10',
        buttonsStyling: false
    }).then(function () {
        post_params.push({ name: '_token', value: $('input[name=_token]').val() });
        AJAXcall(url, post_params);
    });
});

$(document).on('click', '.gallary', function () {
    var post_params = [];

    if ($(this).attr('data-form-id'))
        post_params = $('#' + $(this).attr('data-form-id')).serializeArray();
    else
        post_params.push({ name: 'profile', value: $(this).attr('data-file-name') })

    if ($(this).attr('data-id'))
        post_params.push({ name: 'id', value: $(this).attr('data-id') });

    var conrim_msg = "You won't be able to change this!";
    var url = $(this).attr('data-url');
    swal({
        title: 'Are you sure?',
        text: conrim_msg,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger m-l-10',
        buttonsStyling: false
    }).then(function () {
        post_params.push({ name: '_token', value: $('input[name=_token]').val() });
        AJAXcall(url, post_params);
    });
});

/* As bug issue reported for popover on Stack Overflow i.e, once hide popover >-=→> it takes two clicks to show */
$(document).on('hidden.bs.popover', function (e) {
    $(e.target).data("bs.popover").inState.click = false;
});

$('body').on('click', '.saveBtn, button.saveForm, .removeBtn, .editBtn', function () {
    $('loader').removeClass('d-none');
    var post_params = [];
    if ($(this).attr('data-form-id'))
        post_params = $('#' + $(this).attr('data-form-id')).serializeArray();

    if ($(this).attr('data-img-path'))
        post_params.push({ name: 'img_path', value: $(this).attr('data-img-path') });

    if ($(this).attr('data-id'))
        post_params.push({ name: 'id', value: $(this).attr('data-id') });

    if ($(this).attr('data-status'))
        post_params.push({ name: 'status', value: $(this).attr('data-status') });

    var url = $(this).attr('data-url');

    var confirm_msg = ($(this).attr('data-confirm')) ? ($(this).attr('data-confirm')) : false;
    var url = $(this).attr('data-url');
    post_params.push({ name: '_token', value: $('input[name=_token]').val() });
    if (confirm_msg) {
        swal({
            title: 'Are you sure?',
            text: confirm_msg,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            buttonsStyling: false
        }).then(function () {
            console.log(url);
            AJAXcall(url, post_params);
        });
    } else {
        console.log(post_params);
        AJAXcall(url, post_params);
    }
});

$('body').on('click', 'button.saveImgForm', function () {

    var post_params = new FormData();

    //Form data
    var form_data = $('#' + $(this).attr('data-form-id')).serializeArray();
    form_data.push({ name: '_token', value: $('input[name=_token]').val() });
    $.each(form_data, function (key, input) {
        post_params.append(input.name, input.value);
    });
    console.log(form_data);
    //File data
    if ($('#' + $(this).attr('data-form-id') + ' input[name="file"]').length > 0) {
        var file_data = $('input[name="file"]')[0].files;
        for (var i = 0; i < file_data.length; i++) {
            post_params.append("file", file_data[i]);
        }
    }

    if ($('#' + $(this).attr('data-form-id') + ' input[name="file_2"]').length > 0) {
        var file_data = $('input[name="file_2"]')[0].files;
        for (var i = 0; i < file_data.length; i++) {
            post_params.append("file_2", file_data[i]);
        }
    }

    //Custom data
    // post_params.append('key', 'value');

    var url = $(this).attr('data-url');
    var $btn = $(this).button('loading');
    var other_params = { 'buttonId': $btn };

    console.log(post_params);
    $.ajax({
        url: url,
        method: "post",
        processData: false,
        contentType: false,
        data: post_params,
        success: function (data) {
            var obj = jQuery.parseJSON(data);
            ajaxDone(obj, url, post_params, other_params);
        },
        error: function (e) {
            //error
        }
    });
});

// Load images
function readerURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $($(input).attr('data-reader-img')).html('<img src="' + e.target.result + '" alt="Image name" class="img-fluid img-image">');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on('change', 'input[type="file"], #file', function (e) {
    readerURL(this);
})

$('body').on('click', '.bookService', function () {
    var post_params;

    if ($(this).attr('data-service')) {
        var service = jQuery.parseJSON($(this).attr('data-service'));
        post_params = service;
    }

    if ($(this).attr('data-c-id'))
        post_params.c_id = $(this).attr('data-c-id');

    var url = $(this).attr('data-url');
    post_params._token = $('input[name=_token]').val();
    AJAXcall(url, post_params);
});


$(document).on('click', '.remove_image', function () {
    var name = $(this).attr('data-name');
    var id = $(this).attr('data-id');
    var url = 'file_upload/removeFile';
    AJAXcall(url, { file: name, id: id });
});

$(document).on('click', '.editProfile', function () {
    url = 'file_upload/getFiles';
    params.push({name : '_token', value : $('input[name=_token]').val()});
    AJAXcall(url, params)
});


$(document).on('change', '#cat_id', function () {
    $('#gst').val($('#cat_id').children("option:selected").data('gst'));
    $('#commission').val($('#cat_id').children("option:selected").data('commission'));
    var post_params = [];
    post_params.push({ 'name': 'cat_id', 'value': $(this).val() });
    AJAXcall('service/getSubCategoryOptionHtml', post_params);
});

