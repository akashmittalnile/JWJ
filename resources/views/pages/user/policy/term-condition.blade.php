@extends('layouts.auth-app')
@push('css')
<style>
    h6, h3{
        font-weight: 700;
    }
</style>
@endpush
@section('content')
<div class="w-100 d-flex align-items-center justify-content-center my-3">
    <img src="{{assets('assets/images/logo.svg')}}" alt="" width="120">
</div>
<div>
    <div class="text-center my-4" style="color: #1079c0;">
        <h3>Terms & Condition</h3>
    </div>
    <div class="container my-5">
        <p>This Terms of Service(“Agreement”) is made between Journey with Journals LLC (“Journey with Journals”, “we”, “us”, and “our”) and the purchaser of Journey with Journals app.(“You”, “Your”). This Agreement governs the use of Journey with Journals app, and or Social Media accounts.</p>
        <p>We hope you will enjoy this convenient way to record your experiences while keeping them private. It is our hope that you will share knowledge of this app with others as this is how we can grow.</p>

        <h6>Table of Contents</h6>
        <ul>
            <li>Acceptance</li>
            <li>Our Services</li>
            <li>Accounts</li>
            <li>Subscription Plans</li>
            <li>Your Obligations</li>
            <li>Third Party Material</li>
        </ul>

        <h6>Acceptance</h6>
        <p>By creating an account, viewing videos, making a purchase, downloading our software, or otherwise visiting or using our Services, you accept this Agreement and consent to contract with us electronically.
        </p>
        <p>You accept, by this agreement, that we will honor the person communicating through our mobile app is you. Therefore, you accept the responsibility of keeping your username and password secure. You acknowledge that it is your responsibility to change your password regularly and not share it with others. You accept that we will provide a response through the app understanding that you have kept your side secure.</p>
        <p>You accept, by this agreement, that we will honor the person communicating through our mobile app is you. Therefore, you accept the responsibility of keeping your username and password secure. You acknowledge that it is your responsibility to change your password regularly and not share it with others. You accept that we will provide a response through the app understanding that you have kept your side secure.</p>
        <p>We may update this Agreement by posting a revised version in this location. A notification will be sent to you when a change has been made. By continuing to use our Services, you accept any revised Agreement. This account of our agreement was last updated 8/1/2024.</p>
        <p>Please review our Privacy Policy to learn about the information we collect from you, how we use it, and with whom we share it.</p>

        <h6>Our Services </h6>
        <p>This is a platform that is designed to help you document your journey through life. It gives you a simple way to record the events that you experience from day to day. You can record your thoughts via voice or type, add pictures, and make search labels that will help you find your entry later. You will also be asked to select the mood you are in when you make your entry into your Journal.</p>
        <p>This is a platform that is designed to help you document your journey through life. It gives you a simple way to record the events that you experience from day to day. You can record your thoughts via voice or type, add pictures, and make search labels that will help you find your entry later. You will also be asked to select the mood you are in when you make your entry into your Journal.</p>
        <p>We have created a community section for you to interact with others who share your common interests. You will have the opportunity to view, reply, or create communities based on your subscription plan.</p>
        <p>We have created social media accounts to highlight the many different uses of Journey with Journals and how to use them.</p>

        <h6>Accounts</h6>
        <p>Registration: You must create an account to use this app. To do so, you must provide your name, a preferred user name(for communities), an email address, contact phone number, password, and designate what gender you prefer to use. The gender selection is only used for generic silhouettes in the application and only you can see it. The silhouette will be replaced by your profile picture when you update your app. By creating an account, you agree to receive notices from Journey with Journals LLC at this email address at any time, including before 8:00 a.m. and after 9:00 p.m. local time. You must keep your email address valid and current so that we are able to contact you. Accounts that have email returned will be placed on hold until the email is updated. An account may only be used by one person; login credentials may not be shared by multiple people.</p>
        <p>Accounts in your application are kept private. However, Level B and C allow access to communications with others through the Community Section. We recommend this application for those 13 years and older. If you give access to Level B or C to a minor, you agree and understand that you are responsible for monitoring and supervising your child's usage and interaction with others. You will hold Journey with Journals LLC harmless to activity that is deemed inappropriate.</p>
        <p>Account Security: You are responsible for all activity that occurs under your account, including unauthorized activity. You must protect the confidentiality of your account login information and may not share your account password with anyone. You understand that your account does not log out on your phone unless you click on the logout link. You accept all responsibility if someone accesses your account on your phone. If you become aware of unauthorized access to your account, you must change your password and notify us immediately.</p>

        <h6>Subscription Plans</h6>
        <p>We currently offer three levels of service. Level A, B, and C. Each of these levels offer a different level of service and additional features. You may switch between these levels once every 60 days. Your entries that were entered before the change in level will not be affected.</p>
        <p>In-app Purchase: You have the option to download a PDF formatted copy of your journal entries. You may download as often as you are willing to pay the download fee. There is a time range of up to six months at a time. It will allow you to pick the day you wish to start the six-month range, and the system will add up to the six-month limit. Once you have the PDF it is your responsibility to keep it secure. We do not keep a backup copy. If you lose it you can reprint it for the fee.</p>
        <p>Plans B and C will require a monthly fee. This fee is discounted for a yearly commitment. A form of payment will be required in the app for processing. It is a recurring fee until you close your account. If your form of payment does not process, your account will be frozen until you update your payment information and payment is processed.</p>
        <p>Payment Method: You authorize Journey with Journals LLC to collect payment from your designated payment method or, if payment cannot be completed via your designated payment method, any other payment method you have saved in your Journey with Journals LLC account. You may revoke this authorization with respect to a given payment method at any time by removing such payment method from your account. You will not dispute Journey with Journals recurring transactions with your bank. You must close your account and at that point your recurring payment will cease. There are no refunds. A payment will continue your account until the time period for that payment ends.</p>

        <h6>Your Obligations </h6>
        <p>Your obligations are as follows:</p>
        <ol>
            <li>Use and Enjoy! If you enjoy it feel free to help us spread the word.</li>
            <li>Keep your login credentials safe and secure. Change your password at your discretion. You will not be prompted to change it. Remember you must logout to close the app.</li>
            <li>If you provide this service for a minor and provide access to Level B or C. Teach them all they need to know about keeping safe in online communities. We are not held liable for activities.</li>
            <li>No profanity allowed in the communities. This will remove your access to the communities.</li>
            <li>Make sure you have permission to use any photo you post in a community. No nudity photos are allowed to be posted. This will remove your access to the communities.</li>
            <li>This is not a dating app. Please take personal, indiscreet conversations out of the community. Let the community be enjoyed by everyone.</li>
            <li>Do not make unwanted offers to others in the community. Do not stalk or harass others. This will remove your access to the communities.</li>
            <li>You may not post in the communities any of the following:
                <ol type="a">
                    <li>Is sexually explicit or promotes a sexual service</li>
                    <li>Is defamatory</li>
                    <li>Harassing or abusive statements</li>
                    <li>Things that are hateful or discriminatory</li>
                    <li>Promotes or supports terror or hate groups</li>
                    <li>Anything pertaining to assembling or distributing of explosive devices or weapons</li>
                    <li>Anything enticing or exploiting minors</li>
                    <li>Advertising of selling/trading anything that is unlawful</li>
                    <li>Advertising of selling anything for a business</li>
                </ol>
            </li>
        </ol>

        <h6>Third Party Material </h6>
        <p>The Services may contain links to websites and content owned and/or operated by third parties. Suck links and content are provided for informational purposes only. We ae not responsible for any such third-party websites or content and do not have control over them.</p>
        <p>Third Parties: We may provide links to and integrations with websites or services operated by others. Your use of each such website or service is subject to its terms of service of that service.</p>
        <p>The services offered at the time of purchase are all that you are entitled to. If we enhance the app to include further enhancements, they may be included, or they could be offered for an additional cost.</p>

    </div>
</div>
@endsection