@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/help.css') }}">
@endpush

@section('title','Journey with Journals - Chat')
@section('content')
<meta name="_token" content="{{csrf_token()}}" />
<div class="body-main-content">
    <div class="message-section">

        <div class="chat-section">
            <div class="chat-userlist-sidebar">
                <div class="chat-userlist-sidebar-head">
                    <div class="chat-panel-sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> <path d="M17 9C17 12.87 13.64 16 9.5 16L8.57001 17.12L8.02 17.78C7.55 18.34 6.65 18.22 6.34 17.55L5 14.6C3.18 13.32 2 11.29 2 9C2 5.13 5.36 2 9.5 2C12.52 2 15.13 3.67001 16.3 6.07001C16.75 6.96001 17 7.95 17 9Z" stroke="#4F5168" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M22 12.86C22 15.15 20.82 17.18 19 18.46L17.66 21.41C17.35 22.08 16.45 22.21 15.98 21.64L14.5 19.86C12.08 19.86 9.92001 18.79 8.57001 17.12L9.5 16C13.64 16 17 12.87 17 9C17 7.95 16.75 6.96001 16.3 6.07001C19.57 6.82001 22 9.57999 22 12.86Z" stroke="#4F5168" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M7 9H12" stroke="#7BC043" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> 
                    </div>
                    <h2>Chat</h2>
                </div>
                <div class="chat-userlist-sidebar-body">
                    <div class="chat-userlist-filter">
                        <input type="text" name="" id="searchInput" class="form-control" placeholder="Search by user name">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                    <div class="chat-userlist-info">
                        @foreach($users as $val)
                        <a href="{{ route('admin.chats', encrypt_decrypt('encrypt', $val['id'])) }}">
                            <div class="chat-userlist-item @if(isset($user->id) && ($val['id'] == $user->id)) current-user @endif user-info" data-id="$val->id" data-name="{{ $val['name'] }}" data-img="{{ (isset($val['profile']) && file_exists(public_path('uploads/profile/'.$val['profile']))) ? assets('uploads/profile/'.$val['profile']) : assets('assets/images/avatar.png') }}">
                                <div class="chat-userlist-item-inner" style="width: 78%;">
                                    <div class="chat-userlist-item-image">
                                        <img src="{{ (isset($val['profile']) && file_exists(public_path('uploads/profile/'.$val['profile']))) ? assets('uploads/profile/'.$val['profile']) : assets('assets/images/avatar.png') }}" alt="avatar">
                                        <span class="user-status"></span>
                                    </div>
                                    <div class="chat-userlist-item-content">
                                        <h4>{{ $val['name'] }}</h4>
                                        <p class="text-muted last-message-{{ $val['id'] }}">{{ $val['last_msg'] }}</p>
                                    </div>
                                </div>
                                <div class="chat-userlist-item-date" style="width: 22%;">
                                    <div class="chat-userlist-time text-muted time-message-{{ $val['id'] }}">{{ $val['time'] }}</div>
                                    @if( isset($val['unseen_msg_count']) && $val['unseen_msg_count'] != 0 )
                                    <span class="badge bg-danger rounded-pill float-end unseen-count-{{ $val['id'] }}">{{ $val['unseen_msg_count'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @if(isset($user->id))
            <div class="chat-panel-section">
                <div class="chat-panel-chat-header">
                    <div class="chat-panel-user-item">
                        <div class="chat-panel-user-item-image"><img src="{{ (isset($user->profile) && file_exists(public_path('uploads/profile/'.$user->profile))) ? assets('uploads/profile/'.$user->profile) : assets('assets/images/avatar.png') }}"></div>
                        <div class="chat-panel-user-item-text">
                            <h4>{{ $user->name ?? 'NA' }}</h4>
                            <p>{{ $user->plan->name ?? 'Plan A' }} Member</p>
                        </div>
                    </div>
                </div>
                <div class="chat-panel-chat-body body-chat-message-user" tabindex="1" style="overflow: auto; outline: none;">
                    <div class="chat-panel-chat-content">
                        <div class="messages-list messages-card">
                        </div>
                    </div>
                </div>
                <div class="chat-panel-chat-footer">
                    <div class="row g-1">
                        <div class="col-md-9">
                            <div class="form-group">
                               <input type="text" class="form-control" id="message-input" placeholder="Type message">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <a class="attachment-btn" id="image-attach" href="javascript:void(0)"><i class="las la-paperclip"></i></a> 
                                <input type="file" hidden accept="image/png, image/jpg, image/jpeg" id="upload-file" name="image-attachment"> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn-send btnSend" title="" type="button">
                                 Send
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="ajax-chat-url" value="{{ $user->id }}">
                    <input type="hidden" id="ajax-chat-url-name" value="{{ $user->name }}">
                    <input type="hidden" id="ajax-chat-url-img" value="{{ (isset($user->profile) && file_exists(public_path('uploads/profile/'.$user->profile))) ? assets('uploads/profile/'.$user->profile) : assets('assets/images/avatar.png') }}">
                </div>
            </div>
            @endif
        </div>
    <div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    $(document).on('click', "#image-attach", function() {
        $("input[name='image-attachment']").trigger('click');
    })

    $(document).on('change', "input[name='image-attachment']", function() {
        $('.la-paperclip').css('color', '#0d6efd');
    })
</script>
<script type="module" >
    import {
        getAuth,
        signInAnonymously
    } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-auth.js"
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-app.js";
    import {
        getFirestore,
        collection,
        getDocs,
        addDoc,
        orderBy,
        query
    } from "https://www.gstatic.com/firebasejs/9.1.3/firebase-firestore.js";

    const firebaseConfig = {
        apiKey: "{{ config('constant.apiKey') }}",
        authDomain: "{{ config('constant.authDomain') }}",
        projectId: "{{ config('constant.projectId') }}",
        storageBucket: "{{ config('constant.storageBucket') }}",
        messagingSenderId: "{{ config('constant.messagingSenderId') }}",
        appId: "{{ config('constant.appId') }}"
    };

    const receiver_id = $("#ajax-chat-url").val();
    const group_id = "1-" + receiver_id;
    const app = initializeApp(firebaseConfig);
    let defaultFirestore = getFirestore(app);
    console.log("Firestore => ", defaultFirestore);
    const auth = getAuth(app);
    signInAnonymously(auth)
        .then((result) => {
            console.log(result);
        })
        .catch((error) => {
            console.log('error', error);
            const errorCode = error.code;
            const errorMessage = error.message;
            // ...
        });

    length = 36;
    const characters = '0123456789abcdefghijklmnopqrstuvwxyz'; // characters used in string
    let result = ''; // initialize the result variable passed out of the function
    for (let i = length; i > 0; i--) {
        result += characters[Math.floor(Math.random() * characters.length)];
    }
    let random = result;

    window.sendNewMessage = async function(group_id_new2, message, receiver_id, userName, image = '') {
        // alert(6);
        const chatCol = collection(defaultFirestore, 'jwj_chats/' + group_id_new2 + '/messages');
        const date = new Date();
        function formatDateToUTC(date) {
            const pad = (num) => num.toString().padStart(2, '0'); 
            const year = date.getUTCFullYear();
            const month = pad(date.getUTCMonth() + 1);
            const day = pad(date.getUTCDate());
            const hours = pad(date.getUTCHours());
            const minutes = pad(date.getUTCMinutes());
            const seconds = pad(date.getUTCSeconds());
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }
        let data = {
            text: message ?? "HHH",
            image: image,
            sendBy: '1',
            sendto: receiver_id,
            // sendBy: receiver_id,
            // sendto: '1',
            adminName: 'JourneyWithJournals',
            userName: userName,
            seen: false,
            user: {
                _id: 1
            },
            _id: random,
            createdAt: formatDateToUTC(date)
        };

        let chatMsg
        if((message != null) && (message != '')){
            chatMsg = message;
        } else chatMsg = 'Sent an attachment';

        const add = await addDoc(chatCol, data);
        const chatCols = query(collection(defaultFirestore, 'jwj_chats/' + group_id_new2 + '/messages'), orderBy('createdAt', 'asc'));
        const chatSnapshot = await getDocs(chatCols);
        const chatList = chatSnapshot.docs.map(doc => doc.data());
        $(".last-message-"+receiver_id).text(chatMsg);
        $(".time-message-"+receiver_id).text('Just Now');
        showAllMessages(chatList);

        let form = new FormData();
        form.append('msg', chatMsg);
        form.append('user_id', receiver_id);
        $.ajax({
            type: 'post',
            url: "{{ route('admin.chats.record') }}",
            data: form,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status) {
                    return false;
                } else {
                    console.error(response.message);
                    return false;
                }
            },
            error: function(data, textStatus, errorThrown) {
                jsonValue = jQuery.parseJSON(data.responseText);
                console.error(jsonValue.message);
            },
        });
    }


    window.getClientChat = async function(group_id, ajax_call = false) {
        // console.log("Group ID => ", group_id);
        const chatCols = query(collection(defaultFirestore, 'jwj_chats/' + group_id + '/messages'), orderBy('createdAt',
            'asc'));
        const chatSnapshot = await getDocs(chatCols);
        const chatList = chatSnapshot.docs.map(doc => doc.data());
        // console.log("get client chat => ", chatList);

        showAllMessages(chatList, ajax_call);
    }
    
    getClientChat(group_id);
</script>

<script>
    $.extend($.expr[':'], { //definizione di :conaints() case insesitive
        'containsi': function(elem, i, match, array)
        {
            return (elem.textContent || elem.innerText || '').toLowerCase()
                    .indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });
    $(document).on('keyup','#searchInput',function(){
        let text = $(this).val();
        if(!text){
            $(".chat-userlist-info a").show();
        }else{
            $(".chat-userlist-info a").hide();
            $(".chat-userlist-info a:containsi("+text+")").show();
        }
        let userLength = $(".chat-userlist-info a:visible").length;
        let noDataLength = $(".chat-userlist-info div.d-flex.justify-content-center").length;
        if(userLength == 0 && noDataLength == 0){
            let noData = `<div class="d-flex justify-content-center align-items-center flex-column">
                    <div>
                        <img width="350" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                    </div>
                </div>`;
            $(".chat-userlist-info").append(noData);
        } else if(userLength > 0) {
            $(".chat-userlist-info div.d-flex.justify-content-center").remove();
        }
    });

    const baseChatUrl = "{{ url('/') }}" + '/public/uploads/chat/';
    console.log(baseChatUrl);
    $(document).ready(function() {

        const receiver_id = $("#ajax-chat-url").val();
        
        $(document).on('click', '.btnSend', function() {
            const user_Name = $("#ajax-chat-url-name").val();
            const userName = user_Name;
            const receiver_id = $("#ajax-chat-url").val();
            const group_id = "1-" + receiver_id;
            let message = $('#message-input');
            const date = new Date();
            function formatDateToUTC(date) {
                const pad = (num) => num.toString().padStart(2, '0'); 
                const year = date.getUTCFullYear();
                const month = pad(date.getUTCMonth() + 1);
                const day = pad(date.getUTCDate());
                const hours = pad(date.getUTCHours());
                const minutes = pad(date.getUTCMinutes());
                const seconds = pad(date.getUTCSeconds());
                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }
            let time = formatDateToUTC(date);
            let image = '';
            if ($('#upload-file')[0].files[0]) image = URL.createObjectURL($('#upload-file')[0].files[0]);
            else image = '';
            if (message.val().trim() != '' || image != '') {
                showMessage(message.val(), time, userName, image);
                let formData = new FormData();
                formData.append('image', $('#upload-file')[0].files[0]);
                formData.append('_token', "{{csrf_token()}}");
                if (image !== undefined && image !== '') {
                    $.ajax({
                        type: 'post',
                        url: "{{ route('admin.chats.image') }}",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(res) {
                            console.log(res);
                            if (res.status == false) {
                                alert(res.msg);
                                return false;
                            }
                            if (res.status) {
                                sendNewMessage(group_id, message.val(), receiver_id, userName, res.url);
                                message.val('').focus();
                                $('#upload-file').val('');
                                $('.la-paperclip').css('color', '#6c757d');
                            }
                        }
                    })
                } else {
                    sendNewMessage(group_id, message.val(), receiver_id, userName);
                    message.val('').focus();
                }
            } else return;
        })
    });

    function showAllMessages(list, ajax_call = false) {
        $('.messages-card').html('<div class="no-datafound-content" style="font-size: 1rem;">No messages found</div>');
        if (list.length == 0) return false;
        let html = `${list.map(row => admin(row,ajax_call)).join('')}`;
        $('.messages-card').html(html);
        if (ajax_call == false) {
            $(".body-chat-message-user").stop().animate({
                scrollTop: $(".body-chat-message-user")[0].scrollHeight
            }, 1000);
        }
    }

    function showMessage(message, time, userName, image) {
        // alert(1);
        let msg = ` <div class="message-item outgoing-message">
                         <div class="message-item-chat-card">
                            <div class="message-item-user">
                                <img src="{{ ((auth()->user()->profile=='' || auth()->user()->profile == null) && !file_exists(public_path('uploads/profile/'.auth()->user()->profile))) ? assets('assets/images/avatar.png') : assets('uploads/profile/'.auth()->user()->profile) }}" alt="avatar 1" >
                            </div>
                            <div class="message-item-chat-content">
                                <div class="message-content">
                                    ${(image !== undefined && image !== '') ? `<img style="border: 1px solid #eee; border-radius: 8px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;" src="${image}" alt="avatar"  width="100"/>` : ''}
                                    ${(message !== '' && message !== undefined) ? `<p>${message}</p>` : ''}
                                </div>
                                <div class="time">${ moment.utc(time)?.local()?.format('MMM DD, YYYY hh:mm A') }</div>
                            </div>
                        </div>
                    </div>`;
        $('.messages-card').append(msg);
        $(".body-chat-message-user").stop().animate({
            scrollTop: $(".body-chat-message-user")[0].scrollHeight
        }, 1000);
    }


    function admin(row) {
        let userProImg = ($("#ajax-chat-url-img").val() == "") ? "{{ assets('assets/images/avatar.png') }}" : $("#ajax-chat-url-img").val();
        let html = '';
        var formattedDate = moment.utc(row.createdAt)?.local()?.format('MMM DD, YYYY hh:mm A');
        if (row.sendto == 1) {

            html = `<div class="message-item ">
                         <div class="message-item-chat-card">
                            <div class="message-item-user">
                                <img src="${userProImg}" alt="avatar" class="d-flex align-self-center me-3" width="60" height="60">
                            </div>
                            <div class="message-item-chat-content">
                                <div class="message-content">
                                    ${(row.image !== undefined && row.image !== '') ? `<img style="border: 1px solid #eee; border-radius: 8px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;" src="${baseChatUrl+row.image}" width="100"/>` : ''}
                                    ${(row.text !== '' && row.text !== undefined) ? `<p>${row.text}</p>` : '' }
                                </div>
                                <div class="time">${formattedDate}</div>
                            </div>
                        </div>
                    </div>
                    `;
        } else {
            html = `<div class="message-item  outgoing-message">
                         <div class="message-item-chat-card">
                            <div class="message-item-user">
                                <img src="{{ ((auth()->user()->profile=='' || auth()->user()->profile == null) && !file_exists(public_path('uploads/profile/'.auth()->user()->profile))) ? assets('assets/images/avatar.png') : assets('uploads/profile/'.auth()->user()->profile) }}" alt="avatar 1">
                            </div>
                            <div class="message-item-chat-content">
                                <div class="message-content">
                                    ${(row.image !== undefined && row.image !== '') ? `<a data-fancybox="" href="${baseChatUrl+row.image}"><img style="border: 1px solid #eee; border-radius: 8px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;" src="${baseChatUrl+row.image}" alt="avatar" width="100"/></a>` : ''}
                                    ${(row.text !== '' && row.text !== undefined) ? `<p >${row.text}</p>` : '' }
                                </div>
                                <div class="time">${formattedDate}</div>
                            </div>
                        </div>
                    </div>`;
        }
        return html;
    }
</script>
<script>
    setInterval(function() {
        const receiver_id = $("#ajax-chat-url").val();
        const group_id = "1-" + receiver_id;
        getClientChat(group_id, true);
    }, 5000);
</script>

@endsection