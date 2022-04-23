<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="">
                    <form action="{{route('photo.store')}}" id="my-form" method="post" enctype="multipart/form-data" class="dropzone"></form>
                </div>
            </div>
        </div>
    </div>


<script src="{{asset('js/app.js')}}"></script>
<script>
    Dropzone.autoDiscover = false;

    let myDropzone = new Dropzone("#my-form");
    myDropzone.on("addedfile", file => {
        console.log(`File added: ${file.name}`);
    });
</script>
</body>
</html>
