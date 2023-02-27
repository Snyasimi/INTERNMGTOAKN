<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Acitvate Account</title>
</head>
<body>
    <h1>Sign Up</h1>
    <hr>

    <form action="{{ route('ActivateAccount') }}" method="POST" >
        @csrf
        <p>
        <label for="email">Email 
            <input type="email" name="email" required placeholder="Example@gmail.com">
        </label>
    </p>
    <p>
        <label for="password">Password 
            <input type="password" required name="password">
        </label>
    </p>
    <p>
        <button type="submit">Sign in</button>
        <button type="reset">Clear</button>

    </form>


</body>
</html>