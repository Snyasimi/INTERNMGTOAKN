<!DOCTYPE html>
<html>
    <head>
        <title>Applicants</title>
    </head>

    <body>

        @foreach($applicants as $applicant)

            <p><a href="{{route('Apply.show',$applicant->id) }}">{{ $applicant->Name }}</a></p>
            <p>{{ $applicant->Email}}</p>
            <p><form action="{{route('Apply.destroy',$applicant->id) }}" method="Post">
                @method('DELETE')
                @csrf
                <input type="submit" value="Delete">
                </form></p>

                <p><form action="{{route('Apply.update',$applicant->id)}}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="submit" value="Accept" />
                </form>
            </p>


        @endforeach
    </body>
</html>
