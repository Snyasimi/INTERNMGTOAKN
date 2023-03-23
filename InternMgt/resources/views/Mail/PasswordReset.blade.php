@extends('Mail.layout.layout')
@section('content')
<section>


<table role="presentation" class="main">
    <!-- START MAIN CONTENT AREA -->
    
    <tr>
        <td class="wrapper">
            <table
                role="presentation"
                border="0"
                cellpadding="0"
                cellspacing="0"
            >
                <tr>
                    <td>
                            

          
 <p> We Recieved a request to reset your password</p>

 <p>click the link below to reset your new password</p>
 
 <a href="{{$reset_url}}" target="_blank" class="accept">Reset</a>  

 <p>If you did not request this reset,ignore this message</p>
  
 
                                   
                    </td>
                    
                </tr>
            </table>
        </td>
    </tr>

    <!-- END MAIN CONTENT AREA -->
</table>


</section

@endsection('content')



