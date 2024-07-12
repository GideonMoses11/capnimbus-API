<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Paddipay</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro');
        * {
            font-family: 'Source Sans Pro', sans-serif;
        }
        /* Outlines the grids, remove when sending */
        /* table td { border: 1px solid; } */
        /* CLIENT-SPECIFIC STYLES */

        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }
        /* RESET STYLES */

        img {
            border: 0;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        /* iOS BLUE LINKS */

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        hr {
            width: 100%;
        }
        /* ANDROID CENTER FIX */

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
        /* MEDIA QUERIES */
        .footer-greeting{
            margin: 20px 0 30px 0;
            padding: 0 50px;
        }

        .content-greeting{
            color: #586F7E;
            font-size: 18px;
            font-weight:600;
            margin:30px 0 30px 0;
            padding:0 50px;
        }

        .purchase_footer {
        padding-top: 10px;
        margin-top: 25%;
        /* border-top: 1px solid #EAEAEC; */
        }

        .content-body{
            font-size:14px;
            line-height:30px;
            color:#586F7E;
            padding:0 50px;
        }

        .next-steps-background{
            margin: 50px 0;
            background: #F9FBFF;
            padding: 20px 50px;
        }

        .next-steps-header{
            color: #586F7E;
            font-size: 18px;
            font-weight:600;
        }

        .email-link-button{
            height: 15%;
            width: 30%;
            background-color: #0657CD;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            /* margin-top: 10px; */
        }

        .transaction-details{
            padding:0 50px;
        }

        @media only screen and (max-width:480px)
        {
            .wrapper{
                width: 100% !important;
                padding: 0 0;
            }

            .footer-greeting{
            margin: 20px 0 30px 0;
            padding: 0 20px;
        }

        .content-greeting{
            color: #586F7E;
            font-size: 18px;
            font-weight:600;
            margin:30px 0 30px 0;
            padding:0 20px;
        }

        .content-body{
            font-size:14px;
            line-height:30px;
            color:#586F7E;
            padding:0 20px;
        }

        .next-steps-background{
            margin: 50px 0;
            background: #F9FBFF;
            padding: 20px 20px;
        }

        .email-link-button{
            height: 15%;
            width: 30%;
            background-color: #0657CD;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            /* margin-top: 10px; */
        }

        .transaction-details{
            padding:0 20px;
        }

        .imagefour{
                margin-top: 1rem !important;
            }

            .transamount{
                font-size: 13px !important;
            }

            .transamountword{
                font-size: 11px !important;
                /* padding-left: 35% !important; */
            }

            .transamountwordvol{
                font-size: 11px !important;

            }

        }

        /* .purchase_item {
      padding: 10px 0;
      color: #51545E;
      font-size: 15px;
      line-height: 18px;
    } */
    @media only screen and (max-width:370px) {
        .address {
            font-style: normal;
            font-weight: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            /* identical to box height */
            color: white;
        }

        .jig {
            font-weight: 600;
            text-align: left;
            font-size: 11px !important;
        }

        .jigi {
            font-weight: 600;
            text-align: right !important;
            font-size: 11px !important;
        }

        .support {
            font-style: normal;
            font-weight: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            text-align: right !important;
            /* identical to box height */
            color: white;
        }

        .noms {
            font-style: normal;
            font-weight: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            text-align: left;
            /* identical to box height */
            color: white;
        }

        .seam {
            font-style: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            /* identical to box height */
            color: white;
            }

            .imagefour{
                margin-top: 1rem !important;
            }

            .transamount{
                font-size: 13px !important;
            }

            .transamountword{
                font-size: 11px !important;
                /* padding-left: 35% !important; */
            }

            .transamountwordvol{
                font-size: 11px !important;

            }
        }


    @media only screen and (max-width:600px) {
        .address {
            font-style: normal;
            font-weight: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            /* identical to box height */
            color: white;
        }

        .jig {
            font-weight: 600;
            text-align: left;
            font-size: 11px !important;
        }

        .jigi {
            font-weight: 600;
            text-align: right !important;
            font-size: 11px !important;
        }

        .support {
            font-style: normal;
            font-weight: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            text-align: left;
            /* identical to box height */
            color: white;
        }

        .noms {
            font-style: normal;
            font-weight: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            text-align: left;
            /* identical to box height */
            color: white;
        }

        .seam {
            font-style: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            /* identical to box height */
            color: white;
            }

            .imagefour{
                margin-top: 1rem !important;
            }

            .transamount{
                font-size: 13px !important;
            }

            .transamountword{
                font-size: 11px !important;
                /* padding-left: 35% !important; */
            }

            .transamountwordvol{
                font-size: 11px !important;

            }
        }


    @media only screen and (max-width:500px) {
        .address {
            font-style: normal;
            font-weight: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            /* identical to box height */
            color: white;
        }

        .jig {
            font-weight: 600;
            text-align: left;
            font-size: 11px !important;
        }

        .jigi {
            font-weight: 600;
            text-align: right !important;
            font-size: 11px !important;
        }

        .support {
            font-style: normal;
            font-weight: normal;
            font-size: 9px !important;
            line-height: 12px !important;
            text-align: left;
            /* identical to box height */
            color: white;
        }

        .noms {
            font-style: normal;
            font-weight: normal;
            font-size: 9px !important;
            line-height: 10px !important;
            text-align: left;
            /* identical to box height */
            color: white;
        }

        .seam {
            font-style: normal;
            font-size: 8px !important;
            line-height: 10px !important;
            /* identical to box height */
            color: white;
            }

            .imagefour{
                margin-top: 1rem !important;
            }

            .transamount{
                font-size: 13px !important;
            }

            .transamountword{
                font-size: 11px !important;
                /* padding-left: 35% !important; */
            }

            .transamountwordvol{
                font-size: 11px !important;

            }
        }

        @media only screen and (max-width:360px) {
            html {
                /* margin: auto !important;
                margin-left: 1%!important;
                margin-right: 5%!important;
                margin-top: -11% !important; */
                /* padding-right: 20rem !important;
                padding-left: 10rem !important; */
            }
            .imagefour {

            }

            .transamount{
                font-size: 13px !important;
            }

            .transamountword{
                font-size: 11px !important;
                /* padding-left: 35% !important; */
            }

            .transamountwordvol{
                font-size: 11px !important;

            }
        }

        @media all and (max-width:639px) {
            hr {
                width: auto;
            }
            .wrapper {
                width: 370px!important;
                padding: 0 !important;
            }
            .container {
                width: 300px!important;
                padding: 0 !important;
            }
            .mobile {
                width: 300px!important;
                display: block!important;
                padding: 0 !important;
            }
            /* .img{ width:100% !important; height:auto !important; } */
            *[class="mobileOff"] {
                width: 0px !important;
                display: none !important;
            }
            *[class*="mobileOn"] {
                display: block !important;
                max-height: none !important;
            }
            html {
                /* margin: auto !important;
                margin-left: 1%!important;
                margin-right: 0%!important;
                margin-top: -11% !important; */
            }
            .imagefour{
                margin-top: 1rem !important;
            }

            .transamount{
                font-size: 13px !important;
            }

            .transamountword{
                font-size: 11px !important;
                /* padding-left: 35% !important; */
            }

            .transamountwordvol{
                font-size: 11px !important;


            }
        }

        .nextArea {
            background: #303B50;
            padding: 1.8rem 0rem;
            line-height: 0.9rem;
            color: #fff;
            text-align: center;
        }

        .address {
            font-style: normal;
            font-weight: normal;
            font-size: 13px;
            line-height: 15px;
            /* identical to box height */
            color: white;
        }

        .support {
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 15px;
            text-align: left;
            /* identical to box height */
            color: white !important;
            text-decoration: none !important;

        }

        .noms {
            font-style: normal;
            font-weight: normal;
            font-size: 14px;
            line-height: 20px;
            text-align: left;
            /* identical to box height */
            color: white;
        }

        .seam {
            font-style: normal;
            font-size: 13px;
            line-height: 12px;
            /* identical to box height */
            color: white;
        }

        .username {
            font-style: normal;
            color: #303B50;
            font-size: 19px;
            font-weight: 600;
        }

        .amount {
            font-size: 35px !important;
            color: #303B50;
            /* margin-top: -5% !important;
            margin-bottom: -5% !important; */
        }

        .thedate {
            font-size: 15px !important;
            font-weight: 600;
            color: #303B50;
            margin-top: -3%;
        }

        .jig {
            font-weight: 600;
            text-align: left;
        }

        .jigi {
            font-weight: 600;
            text-align: right !important;
        }
    </style>
</head>
