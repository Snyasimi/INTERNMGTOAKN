<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <form action="{{route('AuthLogin')}}" method="POST">
            @csrf
            <label for="Email">Email:
                <input type="email" name="Email">
            </label>

            <label for="Password">Password:
                <input type="password" name="Password">
            </label>
            <p>
                <button type="submit">Login</button>
        </form>
    </body>
</html>