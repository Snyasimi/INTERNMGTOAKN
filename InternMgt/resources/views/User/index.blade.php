<!DOCTYPE html>
<html>

@if($supervisors)

    @foreach($supervisors as $supervisor)
        <p><h2>{{$supervisor->Name}}</h2></p>
        {{--<p>{{$supervisor->Atachee()}}</p>--}}
    @endforeach
@endif