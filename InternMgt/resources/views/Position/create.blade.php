<!DOCTYPE html>
<html>
    <head>
        <title>DEPTS</title>
    </head>
    <body>

        <form method="POST" action="{{route('Position.store')}}">
            @csrf
            <label for="DeptName">Position
                <input type="text" name="Position" required >
            </label>
            <br>
            <p><button type="submit">Add</button></p>
        </form>
    </body>
</html> 