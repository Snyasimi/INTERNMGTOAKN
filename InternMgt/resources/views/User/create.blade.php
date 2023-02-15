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

            <label for="Role">Department
                <select name="Department">
                    <option value=""></option>
                @foreach($depts as $d)
                    <option value="{{$d->id}}">{{$d->DepartmentName}}</option>
                @endforeach
                </select>
            </label><br>

            <label for="Role">Position
                <select name="Position">
                    <option value=""></option>
                @foreach($position as $p)
                    <option value="{{$p->id}}">{{$p->Position}}</option>
                @endforeach
                </select>
            </label><br>


            <label for="Role">Role
                <select name="Role">
                    <option value=""></option>
                @foreach($roles as $role)
                    <option value="{{$role->id}}">{{$role->Role}}</option>
                @endforeach
                </select>
            </label><br>

            <label for="Supervisor">Supervisor
               <select name="Supervisor">
                    <option value=""></option>
                @foreach($Supervisors as $Supervisor)
                    <option value="{{$Supervisor->id}}">{{$Supervisor->Name}}</option>
                @endforeach

            </label>

            <label for="Password">Password 
                <input type="password" name="password" required>
            </label>


            <p>
                <button type="submit">Submit</button>
                
                <button type="reset">Clear</button>
            </p>

            {{-- @foreach($roles as $role)
            <p>{{$role}}</p>
            @endforeach --}}
        </form>
    </body>
</html>
