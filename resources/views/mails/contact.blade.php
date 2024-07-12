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
                        <h3 class="mail_header">Hello! Paddypay</h3>

                        <ul>
                        <p><strong>Name:</strong> {{ $data['name'] }}</p>
                        <p><strong>E-mail:</strong> {{ $data['email'] }}</p>
                        <p><strong>Subject:</strong> {{ $data['subject'] }}</p>
                        <p><strong>Message:</strong> {{ $data['message'] }}</p>
                        </ul>
                        
                        <br>
 

                    </td>
                </tr>
            </table>
@include('mails.layouts.new_mailfooter')
