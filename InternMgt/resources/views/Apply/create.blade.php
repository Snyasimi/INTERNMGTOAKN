<!DOCTYPE html>
<html>
    <head>
        <title>Application form</title>
    </head>

    <body>
        <form action={{ route('Apply.store')}} method="POST" enctype="multipart/form-data">
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
            
            <label for="Role">Position
                <select name="Position">
                    <option value=""></option>
                @foreach($position as $p)
                    <option value="{{$p->id}}">{{$p->Position}}</option>
                @endforeach
                </select>
            </label><br>

            <label for="Cv">Your CV
                <input type="file" name="Cv" required />
            </label><br>

            <p>
                <button type="submit">Submit</button>
                
                <button type="reset">Clear</button>
            </p>
        </form>
    </body>
</html>
