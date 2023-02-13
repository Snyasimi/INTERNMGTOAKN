<!DOCTYPE html>
<html>
    <head>
        <title>DEPTS</title>
    </head>
    <body>

        <form method="POST" action="{{route('Dept.store')}}">
            @csrf
            <label for="DeptName">DEPT
                <input type="text" name="DeptName" required >
            </label>
            <br>
            <p><button type="submit">Add</button></p>
        </form>
    </body>
</html> 