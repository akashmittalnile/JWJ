<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify Your Email on Journey with journals</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@200;300;400;500;600&display=swap" rel="stylesheet" />
</head>

<body>
    <div class="email-template" style="background: #e6f1f9; padding: 10px;">
        <table align="center" cellpadding="0" cellspacing="0" width="600" style="background: #ffffff;font-family:Calibri, sans-serif; margin: 0 auto; background-size: 100%; padding: 10px 30px 0px 30px;">
            <tr>
                <td style="font-family:tahoma, geneva, sans-serif;color:#29054a;font-size:12px; padding:10px;background: #ffffff;text-align: center;">
                    <a href="javascript:void(0)" title="Journey_with_journals">
                        @php
                            $logo = assets('assets/images/logo.svg');
                        @endphp
                        <img alt="Journey_with_journals" src="data:image/png;base64,{{ base64_encode(file_get_contents($logo)) }}" height="60">
                    </a>
                </td>
            </tr>
            <tr>
                <td style=" padding: 10px;" bgcolor="#ffffff">
                    <h1 style="color: #1079c0;font-size: 20px;text-align: center;font-weight: normal; margin: 10px 0;">Hello user</h1>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">You are receiving this email because you have requested for email verification on Journey with journals platform. Please find the below details for verification.</p>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">One Time Password is {{ $otp ?? '0000' }}</p>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">Best regards,</p>
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">Journey with journals</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>