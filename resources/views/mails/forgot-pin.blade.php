@extends('mails.layouts.mailstyles')
@include('mails.layouts.base')
<table width="640" cellpadding="0" cellspacing="0" border="0" class="wrapper" bgcolor="#FFFFFF">
    <tr>
        <td height="15" style="font-size:10px; line-height:10px;">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="top">

            <table width="500" cellpadding="0" cellspacing="0" border="0" class="container">
                <tr>
                    <td align="left" valign="top" class="desktop">
                        <span class="mail_header" style="font-size: 14px;">Hello! </span> <span style="font-weight:bold;font-size: 16px;">{{$user->name}}</span>
                            <p style="font-size:15px !important;">This email was sent in response to your request to reset your transaction pin on 
                                our platform. To reset your pin, please click on the button below:</p>
                        <br>
                        <div class="email_link_button" style="text-align: center">

                                    <!-- <button style="background:#58427c;color:#fff;padding:10px 20px;" type="submit">Verify your mail</button> -->
                                    <a href="{{url($url)}}" style="background:#58427c;color:#fff;padding:10px 20px;text-decoration:none;" target="_blank" rel="noopener noreferrer">Reset Transaction Pin</a>

                        </div>

                    </td>
                </tr>
            </table>
@include('mails.layouts.new_mailfooter')
