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
                            

                <p>  I am writing to inform you that you have been assigned a supervisor for your upcoming project.<br>
                       Your supervisor's name is {{$SupervisorName}} and their email is {{$SupervisorEmail}}.<br>

                        Your supervisor will be in touch with you shortly to discuss the details of your project and answer any questions you may have.<br>
                         If you have any concerns or issues, please do not hesitate to reach out to them.<br>
                        
                        I wish you all the best in your project.  
                </p>                   
                    </td>
                    
                </tr>
            </table>
        </td>
    </tr>

    <!-- END MAIN CONTENT AREA -->
</table>
@endsection('content')