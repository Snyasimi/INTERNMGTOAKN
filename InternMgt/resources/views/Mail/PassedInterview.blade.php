@extends('Mail.layout.layout')
@section('content')

    {{-- <p> We are pleased to inform you that youre accepted for the internship opportunity</p>
    <p>click here to create a password for yoyr account :  {{$Signin_url}}</p>
    <p>click here to decline the offer : {{$Decline_url}}</p> --}}

    
        <div class="container">
          <div class="card p-6 p-lg-10 space-y-4 p-2 mb-3">
            <h1 class="h2 fw-700">
              Interview Passed
            </h1>
            <p>
                We are pleased to inform you that youre accepted for the internship opportunity
            </p>
            <a class="btn btn-primary p-1 fw-700 w-lg-25 mb-3" href="">Click here to create a password for your account</a>
            <a class="btn btn-danger p-1 fw-700 w-auto mb-2" href="">Click here to decline the offer</a>
          </div>
          {{-- <img class="ax-center mt-10 w-40" src="{{asset("public/logo-color.png")}}" /> --}}
          <div class="text-muted text-center my-6 bg-light-subtle p-2 border border-dark">
            Oaknet Business Limited.
            GA Insurance House. Ralph Bunche Road<br>
            Nairobi, Kenya <br>
          </div>
        </div>
    
@endsection('content')
