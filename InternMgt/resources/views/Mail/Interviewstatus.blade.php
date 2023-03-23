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

													

										Congratulations! You have been accepted for the {{$Position}} role at OAKNET BUSINESS.<br>
                                         We were impressed with your application and believe that your qualifications and experience meet our requirements.<br>

                                        <br>We invite you to attend an interview with us on {{$Date}} at our office .
                                                </p>
                                                           
                                            </td>
                                            
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- END MAIN CONTENT AREA -->
                        </table>

@endsection('content')


