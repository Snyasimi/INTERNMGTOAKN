@extends('Mail.layout.layout')
@section('content')

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
                        <p>Dear {{$Name}},</p>
                        <p>

                I am writing to inform you that you have been selected for the {{$Position}} role at Oaknet Business.<br> 
                Your interview with our team was extremely impressive, and we are excited to offer you an internship role in our organization.<br>
                We believe that you have the skills and knowledge that will be a valuable addition to our team.<br>
                        </p>
                        <p>
                We understand that you may need some time to consider the offer, and we want you to have ample time to make an informed decision.<br>
                        </p>

                                   
                    </td>
                    
                    
                </tr>
               <tr class="flex" >
                   <td>

                     <a href="{{$Signin_url}}" target="_blank" class="accept">Accept</a>
                    </td>

                </tr>
                <tr>
                    <td>
                        <a href="{{$Decline_url}}" target="_blank" class="decline">Decline</a>
                        <strong><em>NOTE Once you decline the offer you will  have to apply again<em></strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- END MAIN CONTENT AREA -->
</table>

    
@endsection('content')
