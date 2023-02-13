<!DOCTYPE html>
<html>
    <head>
        <title>Application form</title>
    </head>

    <body>
        <form action={{ route('User.store')}} method="POST" enctype="multipart/form-data">
            @csrf
            <label for="Name">Name
                <input type="text" name="Name" required>
            </label><br>
            <label for="Email">Email 
                <input type="email" required name="Email"/>
            </label><br>
            <label for="PhoneNumber">PhoneNumber
                <input type="number" required name="PhoneNumber" />
            </label><br>
            <label for="Department">Department
                @foreach($depts as $dept)
                {{$dept->DepartmentName}}<input type ="radio" value="{{$dept->id}}" name="Department">
                @endforeach
            </label><br>
            <label for="Role">Role
                <input type="text" name="Role" required />
            </label><br>
            <label for="Supervisor">Supervisor
               <select name="Supervisor">
                @foreach($Supervisors as $Supervisor)
                    <option value="{{$Supervisor->user_id}}">{{$Supervisor->Name}}</option>
                @endforeach

            </label>
            <label for="Password">Password 
                <input type="password" name="password" required>
            </label>


            <p>
                <button type="submit">Submit</button>
                
                <button type="reset">Clear</button>
            </p>
        </form>
    </body>
</html>
