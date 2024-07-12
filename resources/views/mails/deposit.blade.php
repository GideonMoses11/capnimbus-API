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
                        <h3 class="mail_header" style="font-size:16px !important;">Hello! {{$deposit->user->name}}</h3>

                        <p style="font-size:15px !important;">You have successfully made a deposit of <span style="font-weight:bold;">&#8358;{{ number_format($deposit->amount_naira, 2) }}</span> 
                        worth <span style="font-weight:bold;">{{ number_format($deposit->amount_bnb, 2) }} BNB</span>
                        into buyBNB's bank account, pending confirmation from our administrators.
                        </p>
                            <p style="font-size:15px !important;">Sincerely,</p>
                            <p style="font-size:15px !important;">The BuyBNB Team</p>
                        <br>
                        <div class="email_link_button" style="text-align: center">

                        </div>

                    </td>
                </tr>
            </table>
@include('mails.layouts.new_mailfooter')
