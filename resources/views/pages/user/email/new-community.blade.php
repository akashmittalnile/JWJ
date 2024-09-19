<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Community Approval Request</title>
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
                <td valign="top">
                    <p style="font-size:18px;font-weight: 600;line-height: 24px;text-align:left;color: #767171;margin: 10px 0;">Hello {{$customer_name ?? 'user'}},</p>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <p style="font-size: 18px;font-weight: normal;line-height: 24px;text-align:left;color: #767171;">Your request for Community creation has been submitted successfully we will notify you once your Community is approved.</p>
                </td>
            </tr>
            <tr>
                <td valign="top" style="padding: 0 10px;">
                    <p style="font-size: 14px;font-weight: normal;line-height: 24px;text-align:justify;color: #767171;">If you have any questions along the way, don’t hesitate to reach out to our support team at <a style="color: #0563C1" href="mailto:JourneyWJournals@gmail.com">JourneyWJournals@gmail.com</a>.</p>
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