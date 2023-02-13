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
        <object data={{$cv}} type="image/jpeg" width="50%" height="50%">
            <p>Your breowser cannot display</p>
        </object>

    </body>
</html>
