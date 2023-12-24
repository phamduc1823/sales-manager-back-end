<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="{{route('upload')}}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" id="">
    <input type="submit" value="upload">
</form>

<p>
    <span>{{$url}}</span>
    <img src="{{$url}}" alt="">
{{--    <iframe src="https://drive.google.com/file/d/1ja0UGkFMkm2mG1WtMuG0XO14cZmyOzo9/preview" width="640" height="480"></iframe>--}}
</p>
</body>
</html>
