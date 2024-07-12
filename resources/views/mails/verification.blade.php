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
                        <span class="mail_header" style="font-size: 14px;">Hello!</span> <span style="font-weight:bold;font-size: 16px;">{{$user->name}}</span>
                            <p style="font-size: 15px !important;">Thank you for creating an account on Paddipay.</p>
                            <p style="font-size: 15px !important;">To complete your registration, please kindly confirm your email address 
                                by clicking on the link below.</p>
                        <br>
                        <div class="email_link_button" style="text-align: center">

                                    <!-- <button style="background:#58427c;color:#fff;padding:10px 20px;" type="submit">Verify your mail</button> -->
                                    <a href="{{url($url)}}" style="background:#58427c;color:#fff;padding:10px 20px;text-decoration:none;" target="_blank" rel="noopener noreferrer">Verify your mail</a>
                                    <br><br><br><span style="font-size:14px;">You can copy this code: </span> <span style="font-size:16px;font-weight:bold"> {{$user->verification_code}}</span>
                                    
                        </div>
                        <br><p style="font-size: 15px !important;">You are receiving this email because you signed up to Paddipay. 
                            If this was sent to you by mistake, please kindly ignore.</p>

                    </td>
                </tr>
            </table>
@include('mails.layouts.new_mailfooter')
