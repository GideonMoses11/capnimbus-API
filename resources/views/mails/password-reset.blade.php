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
                    <td align="center" valign="top" class="desktop">
                <tr>

                    <td>

                        <h3 class="content-greeting">Hi {{$user->first_name}},</h3>
                        <p class="content-body">

                            This email was sent in response to your request to reset your password on
                            {{config('front_url')}}

                            <br>

                            <br>

                            To reset your password, please click on the button below:

                        </p>


                        <table align="center" cellpadding="0" cellspacing="0" width="500" class="wrapper"
                               bgcolor="ffffff"
                               style="text-align:center">

                            <tbody>

                            <tr>

                                <td>

                                    <a href="https://www.jiggle.ng/user/password-reset/{{$passwordReset->reference}}">
                                        <button type="button" class="email-link-button">Reset Password</button>
                                    </a>

                                </td>

                            </tr>
                            <tr>
                            </tr>

                            </td>
                            </tr>
                        </table>
@include('mails.layouts.new_mailfooter')