<!DOCTYPE html>
<html>
    <head>
        <title>DEPTS</title>
    </head>
    <body>

        <form method="POST" action="{{route('Role.store')}}">
            @csrf
            <label for="DeptName">Role
                <input type="text" name="Role" required >
            </label>
            <br>
            <p><button type="submit">Add</button></p>
        </form>
    </body>
</html> 