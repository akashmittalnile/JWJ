<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Journey with Journals - Registration Successful</title>
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
                    <h1 style="color: #fc4a26;font-size: 20px;text-align: center;font-weight: normal; margin: 10px 0;">
                        Congratulations, {{ $customer_name ?? 'user' }}!</h1>
                </td>
            </tr>
            <tr>
                <td valign="top" style="padding: 0 10px;">
                    <p style="font-size:16px;font-weight: 600; text-align:center;line-height: 24px;color: #767171;margin: 10px 0;">
                    Thanks for registering on the journey with journals we have received your request to join the account. We will send you an email once your account will be approved usually the waiting time is around 24-48 hours thanks for your patience.</p>
                </td>
            </tr>

            <tr>
                <td valign="top" style="padding: 0 10px;">
                    <p style="font-size: 14px;font-weight: normal;line-height: 24px;text-align:justify;color: #767171;">
                        Please save this email address in your Contacts list and let us know if you have any
                        questions. Our email is <a style="color: #0563C1" href="mailto:jwj@gmail.com">jwj@gmail.com</a>, or call
                        us at 1-800-123-1212 if you need assistance.</p>
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