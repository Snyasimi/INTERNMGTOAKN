<!DOCTYPE html>
<html>
    <head>
        <title>Application form</title>
    </head>

    <body>
        <form action={{ route('Task.store')}} method="POST">
            @csrf
        
            <label for="AssignedTo">Assigned To
                <select name="AssignedTo" required>
                    @foreach($Interns as $Intern)
                        <option value="{{$Intern->user_id}}">{{$Intern->Name}}</option>
                    @endforeach
                </select>

            </label><br>
            <label for="Task">Task 
                <input type="text" required name="Task"/>
            </label><br>
            <label for="Task">Task Description
                <input type="text" required name="TaskDescription"/>
            </label><br>
            <label for="Deadline">Deadline
                <input type="date" required name="Deadline" />
            </label><br>
           

            <p>
                <button type="submit">Submit</button>
                
                <button type="reset">Clear</button>
            </p>
        </form>
    </body>
</html>
