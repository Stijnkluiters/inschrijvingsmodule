<?php
/**
 * Created by PhpStorm.
 * User: Johan Vd Wetering
 * Date: 19-12-2017
 * Time: 15:30
 */
// onderwerp
$subject= 'Bevestiging uitschrijving';
$message = '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
              xmlns:o="urn:schemas-microsoft-com:office:office">
        <head><title></title>  <!--[if !mso]><!-- -->
            <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!--<![endif]-->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style type="text/css">  #outlook a {
                padding: 0;
            }

            .ReadMsgBody {
                width: 100%;
            }

            .ExternalClass {
                width: 100%;
            }

            .ExternalClass * {
                line-height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }

            table, td {
                border-collapse: collapse;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
            }

            img {
                border: 0;
                height: auto;
                line-height: 100%;
                outline: none;
                text-decoration: none;
                -ms-interpolation-mode: bicubic;
            }

            p {
                display: block;
                margin: 13px 0;
            }</style><!--[if !mso]><!-->
            <style type="text/css">  @media only screen and (max-width: 480px) {
                @-ms-viewport {
                    width: 320px;
                }    @viewport {
                    width: 320px;
                }
            }</style>
            <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet"
                  type="text/css">
            <style type="text/css">        @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);    </style>
            <!--<![endif]-->
            <style type="text/css">  @media only screen and (min-width: 480px) {
                .mj-column-per-40 {
                    width: 40% !important;
                }

                .mj-column-per-60 {
                    width: 60% !important;
                }

                .mj-column-per-100 {
                    width: 100% !important;
                }
            }</style>
        </head>
<body style="background: #FFFFFF;">
<div class="mj-container" style="background-color:#FFFFFF;">
    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" border="0">
        <tbody>
        <tr>
            <td>
                <div style="margin:0px auto;max-width:600px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;"
                           align="center" border="0">
                        <tbody>
                        <tr>
                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;">
                                <div class="mj-column-per-40 outlook-group-fix"
                                     style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="word-wrap:break-word;font-size:0px;padding:33px 33px 33px 33px;"
                                                align="center">
                                                <table role="presentation" cellpadding="0" cellspacing="0"
                                                       style="border-collapse:collapse;border-spacing:0px;"
                                                       align="center" border="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width:174px;"><img alt="Roc midden Nederland"
                                                                                      title="" height="auto"
                                                                                      src="https://topolio.s3-eu-west-1.amazonaws.com/uploads/5a2e5baa87c70/1512987615.jpg"
                                                                                      style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;"
                                                                                      width="174"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mj-column-per-60 outlook-group-fix"
                                     style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="word-wrap:break-word;font-size:0px;padding:0px 20px 0px 20px;"
                                                align="left">
                                                <div style="cursor:auto;color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:left;">
                                                    <p>Beste '.formatusername($leerling).',<br><br>Je bent uitgeschreven voor '.date("d-M-y H:i",strtotime($inschrijving['created_at'])).' voor
                                                        het evenement&#xA0;.<br>&#xA0;<br>Op '.date("d-M-y H:i",strtotime($inschrijving['aangemeld_op'])).' ben je op
                                                        <strong>uitgeschreven voor</strong>:</p>
                                                    <table align="center" border="2" cellpadding="1" cellspacing="2"
                                                           style="width:100%;"
                                                           summary="Je kan je opnieuw inschrijven via de website!">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">Toegangscode</th>
                                                            <th scope="col">'.md5($leerling['leerlingnummer'].$evenement['evenement_id']).'</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><strong>Datum</strong></td>                                                            
                                                            <td>'. date('d-M-Y',strtotime($evenement['begintijd'])) . ' tot ' . date("d-M-Y",strtotime($evenement['eindtijd'])) . '</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Tijd</strong></td>                                                            
                                                            <td>'. date('H:i',strtotime($evenement['begintijd'])) . ' tot ' . date("H:i",strtotime($evenement['eindtijd'])) . '</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Titel</strong></td>
                                                            <td>'.ucfirst($evenement['titel']).'</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Omschrijving</b></td>
                                                            <td>'.ucfirst($evenement['omschrijving']).'</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <p></p>
                                                    <p>Met vriendelijke groet,</p>
                                                    <p><br>ROC midden Nederland</p></div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div style="margin:0px auto;max-width:600px;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center"
               border="0">
            <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:9px 0px 9px 0px;">
                    <div class="mj-column-per-100 outlook-group-fix"
                         style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody></tbody>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    </div>
</body>
</html>';
