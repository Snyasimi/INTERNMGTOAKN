@extends('Mail.layout.layout')
@section('content')

    <p> We are pleased to inform you that youre accepted for the internship opportunity</p>
    <p>click here to create a password for yoyr account :  {{$Signin_url}}</p>
    <p>click here to decline the offer : {{$Decline_url}}</p>
    
@endsection('content')
