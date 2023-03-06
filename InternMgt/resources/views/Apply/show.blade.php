<!DOCTYPE html>
<html>
    <head>
        <title>INFO</title>
    </head>

    <body>

        <p>{{$applicant->Name}}</p>
        <p>{{$applicant->Email}}</p>
        <p>{{$applicant->Position}}</p>
        <br>
	{{--CHANGE OBJECT TO PDF--}}
	<img src = "{{$cv}}" >
        <object data="{{$cv}}" width="100%" height="100%" >
            <p>Your breowser cannot display</p>
        </object>

    </body>
</html>
