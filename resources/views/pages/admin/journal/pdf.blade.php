<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <title></title> 
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- CSS Reset : BEGIN -->
<style>
html,
body {
    margin: 0 auto !important;
    padding: 0 !important;
    width: 80% !important;
    text-align: center;
    font-family: mom;
}

.montserrat-<uniquifier> {
  font-family: "Montserrat", sans-serif;
  font-optical-sizing: auto;
  font-weight: <weight>;
  font-style: normal;
}

ul.btn-group li{
    display: inline-block;
}

li{
    list-style: none;
}


h5, h3, p{
    margin: 0;
}

.heading-secondary tr td p{    
    font-size: 30px;
    font-weight: 600;
    font-family: "Montserrat", sans-serif;
    line-height: 50px;}

.heading-name tr td p{    
    font-size: 30px;
    font-family: "Montserrat", sans-serif;
    font-weight: 600;
    line-height: 50px;}

.heading-name tr td{
/*    border: 1px solid #ccc;*/
/*    padding: 30px;*/
    margin-top: 50px;
}


.heading-secondary tr td{
    border: 1px solid #ccc;
    padding: 30px;
    margin-top: 50px;
}

.heading-name {
    border: 1px solid #ccc;
    padding: 30px;
    margin-top: 50px;
}
.heading-name tr td p{
    font-size: 30px;
    font-weight: 600;
    text-align: center;
    font-family: "Montserrat", sans-serif;
    line-height: 50px;
}

table.heading-name{
    width: 100%;
}

.journal-description{
    border: 1px solid #ccc;
    width: 100%;
    padding: 30px;
    text-align: center;
}


</style>

</head>

<body width="100%" style="margin: 0; padding: 0 0 20px 0 !important; font-family: Montserrat, sans-serif;">
    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto; height: 1050px;">
        <tr>
          <td valign="middle">
            <table align="center">
                <tr>
                    <td>
                        @php
                        $logo = assets('assets/images/logo.svg');
                        @endphp
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logo)) }}" alt="" style="width: 160px; height: auto; margin:20px 0px 14px; display: block;">
                    </td>
                </tr>
            </table>
          </td>
        </tr><!-- end tr -->
        <tr>
          <td valign="middle">
            <table align="center" class="heading-name">
                <tr>
                    <td align="center">
                        <p>The Journal of</p>
                    </td>
                </tr>

                <tr>
                    <td align="center">
                        <p><color>{{ $user->name ?? 'NA' }}</color></p>
                    </td>
                </tr>

                <tr>
                    <td align="center">
                        
                        <p>{{ date('m-d-Y', strtotime($date)) }} - {{ date('m-d-Y', strtotime("+6 months $date")) }}</p>
                    </td>
                </tr>
            </table>
          </td>
        </tr><!-- end tr -->
    </table>

    @php
        $startDate = date('m-d-Y', strtotime($journals[0]->created_at));
        $count = 0;
    @endphp
    @forelse($journals as $key => $data)
    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto; height: 1050px">
        @php 
            $loopDate = date('m-d-Y', strtotime($data->created_at));
        @endphp
        <tr>
          <td valign="top">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="heading-secondary">
                <tr>
                    @php
                    $profile = isset($data->user->profile) ? assets('uploads/profile/'.$data->user->profile) : assets('assets/images/no-image.jpg');
                    @endphp
                    <!-- <td><img src="data:image/png;base64,{{ base64_encode(file_get_contents($profile)) }}" style="border-radius: 50%; height: 40px; width: 40px; margin-right: 10px;"></td> -->
                    <td width="80%" style="text-align: left;">
                        <p style="font-family: Montserrat, sans-serif;">Journal with Journals</p>
                        <p style="font-family: Montserrat, sans-serif;">{{ date('m-d-Y h:i a', strtotime($data->created_at)) }}</p>
                        @php
                        if($startDate == $loopDate)
                            $count++;
                        else {
                            $startDate = $loopDate;
                            $count = 1;
                        }
                        @endphp
                        <p style="font-family: Montserrat, sans-serif;" > {{$count}}<sup>{{numbers($count)}}</sup> Entry (of Day)</p>
                    </td>
                    <td width="20%" style="text-align: right;">
                        <ul>
                            @php
                            $moodImg = isset($data->mood->logo) ? assets('assets/images/'.$data->mood->logo) : assets('assets/images/no-image.jpg');
                            @endphp
                            <li><img src="data:image/png;base64,{{ base64_encode(file_get_contents($moodImg)) }}" height="100px"></li>          
                        </ul>
                    </td>
                    <!-- <td><h3 style="margin-left: 10px;">{{ $data->mood->name ?? 'Mood name' }}</h3></td> -->
                </tr>
            </table>
          </td>
        </tr>=

        <tr>
            <td valign="middle">
                <table align="center" class="journal-description">
                    <tr><td><h3>{{ $data->content ?? 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Natus voluptatem eveniet consequuntur sint vel aut esse debitis voluptatum. Exercitationem ipsam mollitia ipsa et error natus, labore nobis vero dignissimos maiores?' }}</h3></td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td valign="">
                <table align="center">
                    <tr>
                        @forelse($data->images as $val)
                        @php $img = (isset($val->name) && file_exists(public_path('uploads/journal/'.$val->name))) ? assets('uploads/journal/'.$val->name) : assets('assets/images/no-image.jpg')  @endphp
                        <td>
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($img)) }}" alt="" 
                            style="width: auto; height: 160px; margin:20px 0px 14px; display: block;">
                        </td>
                        @empty
                        @php $img = assets('assets/images/no-image.jpg'); @endphp
                        <td>
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($img)) }}" alt="" 
                            style="width: auto; height: 160px; margin:20px 0px 14px; display: block;">
                        </td>
                        @endforelse
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @empty
    @endforelse
</body>