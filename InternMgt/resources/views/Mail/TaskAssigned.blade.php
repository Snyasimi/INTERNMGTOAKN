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
                            

                <p>You have been assigned a Task </p>
				<p> Your task is to {{$Task}}</p>
                        
                                   
                    </td>
                    
                </tr>
            </table>
        </td>
    </tr>

    <!-- END MAIN CONTENT AREA -->
</table>
@endsection('content')
