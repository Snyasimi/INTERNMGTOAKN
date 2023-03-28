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
                            
   
                        You have been assigned a new Task.<br>
                        Your task is to {{$task}}.<br>
                        Please complete it by {{$Deadline}}.

                         Let me know if you have any questions or concerns regarding the task.
                          I am available to assist you in any way I can.
                                   
                    </td>
                    
                </tr>
            </table>
        </td>
    </tr>

    <!-- END MAIN CONTENT AREA -->
</table>
@endsection('content')
