viewport_width = 200;
viewport_height = 200;
// profile crop and upload js
$image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width: viewport_width,
      height: viewport_height,
      type:'square' //circle
    },
    boundary:{
      width: viewport_width + 100,
      height: viewport_height + 100
    },
});

$(document).on('change', '#upload-profile, #file', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#crop-upload-image-modal').modal('show');
});

$('.crop_image').click(function(event){
    var userFileType = $('input[name=userFileType]').val();
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'original'
    }).then(function(response){
        url = '/user/profileUpload';
        AJAXcall(url, {"file": response, 'userFileType' : $('input[name=userFileType]').val(), 'deviceFileType' : 'original'});
    });
});