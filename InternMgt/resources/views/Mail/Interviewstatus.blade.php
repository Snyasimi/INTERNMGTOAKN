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

										Congratulations on being shortlisted for this exciting opportunity. 
                                         We were impressed with your application and believe that your qualifications and experience meet our requirements.<br>

                                        <br>We invite you to attend an interview with us on {{$Date}} at our office. If you have any questions or concerns,please do not hesitate to contact us.
                                                </p>
                                                           
                                            </td>
                                            
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- END MAIN CONTENT AREA -->
                        </table>

@endsection('content')


