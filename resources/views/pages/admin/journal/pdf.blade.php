<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <title></title> 

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS Reset : BEGIN -->
<style>
html,
body {
    margin: 0 auto !important;
    padding: 0 !important;
    width: 80% !important;
    text-align: center;
    font-family:'Montserrat', sans-serif;
}

ul.btn-group li{
    display: inline-block;
}

li{
    list-style: none;
}

.admincommunity-text{
    background: #e8f6ff;
    border: 1px solid #1079c0;
    padding: 5px 10px;
    display: inline-block;
    font-size: 12px;
    color: #1079c0;
    border-radius: 5px;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
    box-shadow: 0px 0px 13px 0px rgba(0, 0, 0, 0.05);
    line-height: normal;
}

.toggle__input:checked ~ .toggle__fill {
    background: #4cba08;
}

.toggle__input:checked ~ .toggle__fill::after {
    transform: translateX(24px);
}

.toggle__fill::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    height: calc(50px / 2);
    background: #ffffff;
    box-shadow: 0px 0px 13px rgb(0 0 0 / 5%);
    border-radius: 40px;
    transition: transform 0.2s;
    width: calc(50px / 2);
}

.toggle__fill {
    position: relative;
    width: 50px;
    height: calc(50px / 2);
    border-radius: 50px;
    background: #e5e5f5;
    transition: background 0.2s;
}

.toggle__input {
    display: none;
}

h5, h3, p{
    margin: 0;
}

</style>

</head>

<body width="100%" style="margin: 0; padding: 0 0 20px 0 !important; mso-line-height-rule: exactly; border: 1px solid #e7f5ff;">

@forelse($journals as $data)
    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
        <tr>
          <td valign="top" style="padding: 1em 2.5em; background-color: #e7f5ff; margin-top: 10px">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    @php
                    $profile = isset($data->user->profile) ? assets('uploads/profile/'.$data->user->profile) : assets('assets/images/no-image.jpg');
                    @endphp
                    <td><img src="data:image/png;base64,{{ base64_encode(file_get_contents($profile)) }}" style="border-radius: 50%; height: 40px; width: 40px; margin-right: 10px;"></td>
                    <td width="40%" style="text-align: left;">
                        <h3>{{ $data->user->name ?? 'NA' }}</h3>
                        @php
                        $planImg = isset($data->user->plan->image) ? assets('assets/images/'.$data->user->plan->image) : assets('assets/images/freeplan.svg');
                        @endphp
                        <p> <img src="data:image/png;base64,{{ base64_encode(file_get_contents($planImg)) }}" height="16px"> {{ $data->user->plan->name ?? 'Plan A' }} Member</p>
                    </td>
                    <td width="60%" style="text-align: right;">
                        @php
                        $moodImg = isset($data->mood->logo) ? assets('assets/images/'.$data->mood->logo) : assets('assets/images/no-image.jpg');
                        @endphp
                        <ul>
                            <li><img src="data:image/png;base64,{{ base64_encode(file_get_contents($moodImg)) }}" height="60px"></li>
                        </ul>
                    </td>
                    <td><h3 style="margin-left: 10px;"> {{ $data->mood->name ?? 'Mood name' }}</h3></td>
                </tr>
            </table>
          </td>
        </tr><!-- end tr -->
        <tr>
          <td valign="middle">
            <table align="center">
                <tr>
                @forelse($data->images as $val)
                <td>
                    @php $img = isset($val->name) ? assets('uploads/journal/'.$val->name) : assets('assets/images/no-image.jpg')  @endphp
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($img)) }}" alt="" style="width: 250px; height: auto; margin:10px 0px 14px; display: block;">
                </td>
                @empty
                @endforelse
                </tr>
            </table>
          </td>
        </tr><!-- end tr -->
        
        <tr>
          <td valign="top" >
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="60%" style="text-align: center;">
                        <ul class="btn-group" style="margin: 30px 0 20px 0;">
                            @foreach($data->searchCriteria as $val)
                                <li><div class="admincommunity-text" style="margin-right: 5px;">{{ $val->criteria->name ?? 'NA' }}</div></li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </table>
          </td>
        </tr><!-- end tr -->

        <tr>
          <td valign="middle">
            <table align="left">
                <tr><h3 style="margin: 0 0 20px 0;">{{ $data->title ?? 'Title here' }}</h3></tr>
            </table>
          </td>
        </tr>

        <tr>
          <td valign="middle">
            <table align="center">
                <tr><h5 style="margin: 0 0 20px 0;">{{ $data->content ?? 'Content here' }}</h5></tr>
            </table>
          </td>
        </tr><!-- end tr -->

        <!-- <tr>
          <td valign="middle">
            <table align="center">
                <tr><h2>Status</h2></tr>
            </table>
          </td>
        </tr> -->

        <!-- <tr>
          <td valign="middle">
            <table align="center" class="switch-toggle">
                <tr>
                    <td><p style="color: #8C9AA1;">Inactive</p></td>
                    <td>
                        <label class="toggle" for="myToggle">
                            <input data-id="N0xtUk5XcjJTc2VqQjU4K2tuVGdlUT09" class="toggle__input" name="status" checked="" type="checkbox" id="myToggle">
                            <div class="toggle__fill"></div>
                        </label>
                    </td>
                    <td><p style="color: #8C9AA1;">Active</p></td>
                </tr>
            </table>
          </td>
        </tr> -->

        <tr>
          <td valign="middle">
            <table align="center">
                <tr>
                    <td>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents('http://3.144.121.102/public/assets/images/calendar.svg')) }}">
                    </td>
                    <td>
                        <p> Created Date: {{ date('m-d-Y h:i a', strtotime($data->created_at)) }}</p>
                    </td>
                </tr>
            </table>
          </td>
        </tr><!-- end tr -->
    </table>
@empty
@endforelse
</body>