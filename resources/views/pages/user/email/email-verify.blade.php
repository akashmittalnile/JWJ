<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Journey with Journals - Email Verification Code</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@200;300;400;500;600&display=swap" rel="stylesheet" />
</head>

<body>
    <div class="email-template" style="background: #e6f1f9; padding: 10px;">
        <table align="center" cellpadding="0" cellspacing="0" width="600" style="background: #ffffff;font-family:Calibri, sans-serif; margin: 0 auto; background-size: 100%; padding: 10px 30px 0px 30px;">
            <tr>
                <td style="font-family:tahoma, geneva, sans-serif;color:#29054a;font-size:12px; padding:10px;background: #ffffff;text-align: center;">
                    <a href="javascript:void(0)" title="Journey_with_journals">
                        <img alt="Journey_with_journals" src="{{ assets('assets/images/logo.svg') }}" height="60">
                    </a>
                </td>
            </tr>
            <tr>
                <td style=" padding: 10px;" bgcolor="#ffffff">
                    <h1 style="color: #1079c0;font-size: 20px;text-align: center;font-weight: normal; margin: 10px 0;">
                        {{ $user ?? 'user'}} Email Verification Code</h1>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">
                        You recently requested to verify your email address. If you made this request, please use this OTP-{{ $otp ?? '0000' }} to verify your email address.</p>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">
                        You can safely ignore this email if you didn’t request a email verification. For any questions about your account, please <a href="javascript:void(0)">contact us</a>.</p>
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">
                        Protect your identity; please never reply to or forward this email.</p>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">
                        Thank you,</p>
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">
                        Your Journey with Journals Team</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>