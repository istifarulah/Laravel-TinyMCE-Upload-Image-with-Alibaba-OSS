<!DOCTYPE html>
<html>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/API_KEY/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  </head>
  <body>
    <textarea>
      Welcome to TinyMCE!
    </textarea>
    <script>
      var siteUrl = window.location.protocol+'//'+window.location.host;

      function image_upload(blobInfo, success, failure) {
        var xhr, formData;

        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '/upload');

        xhr.onload = function() {
          var json;

          if (xhr.status != 200) {
            failure('HTTP Error: ' + xhr.status);
            return;
          }

          console.log(xhr.responseText);
          json = JSON.parse(xhr.responseText);

          if (!json || typeof json.location != 'string') {
            failure('Invalid JSON: ' + xhr.responseText);
            return;
          }

          success(json.location);
        };

        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        xhr.send(formData);
      }

      tinymce.init({
        selector: 'textarea',
        plugins: 'image',
        toolbar: 'image',
        height: '400',

        images_upload_url: '/upload',
        images_upload_handler : image_upload,
      });
    </script>
  </body>
</html>