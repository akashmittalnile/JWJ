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
                        <input type="text" name="" id="searchInput" class="form-control" placeholder="Search by user name & email address">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                    <div class="chat-userlist-info" id="appendData">
                    </div>
                </div>
            </div>
            <div class="chat-panel-section">
                <div class="chat-panel-chat-header">
                    <div class="chat-panel-user-item">
                        <div class="chat-panel-user-item-image"><img src="images/user-default.png"></div>
                        <div class="chat-panel-user-item-text">
                            <h4>Mark Jane</h4>
                            <p>Platinum Member</p>
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
                        <div class="col-md-1">
                            <div class="userAvatar-group">
                               <img  src="{{ assets('assets/images/user.svg') }}" alt="avatar" id="userAvatar">
                            </div>
                        </div>
                        <div class="col-md-8">
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
                    <input type="hidden" id="ajax-chat-url" value="">
                    <input type="hidden" id="ajax-chat-url-name" value="">
                    <input type="hidden" id="ajax-chat-url-img" value="">
                </div>
            </div>
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

    $(document).on('click', '.user-info', function() {
        $("#ajax-chat-url").val($(this).attr('data-id'));
        $("#ajax-chat-url-name").val($(this).attr('data-name'));
        $("#ajax-chat-url-img").val($(this).attr('data-img'));
        $(".body-chat-message-user").removeClass('d-none');
        let userAvaImg = ($("#ajax-chat-url-img").val() == "") ? "{{ assets('assets/images/user.svg') }}" : $("#ajax-chat-url-img").val();
        // console.log(userAvaImg);
        $("#userAvatar").attr('src', userAvaImg);
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
        apiKey: "AIzaSyBopYjZG97AO9Da83d5AzsBXrdlNBFvPqk",
        authDomain: "chatapp-29659.firebaseapp.com",
        projectId: "chatapp-29659",
        storageBucket: "chatapp-29659.appspot.com",
        messagingSenderId: "1010083815791",
        appId: "1:1010083815791:web:e26a047de4420d04a3889a"
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
        let data = {
            text: message ?? "HHH",
            image: image,
            sendBy: '1',
            sendto: receiver_id,
            // sendBy: receiver_id,
            // sendto: '1',
            adminName: 'JourneyWithJournals',
            userName: userName,
            user: {
                _id: 1
            },
            _id: random,
            createdAt: new Date()
        };

        // console.log("Data => ", data);

        const add = await addDoc(chatCol, data);
        const chatCols = query(collection(defaultFirestore, 'jwj_chats/' + group_id_new2 + '/messages'), orderBy('createdAt', 'asc'));
        const chatSnapshot = await getDocs(chatCols);
        const chatList = chatSnapshot.docs.map(doc => doc.data());
        $(".last-message-"+receiver_id).text(message ?? image);
        $(".time-message-"+receiver_id).text('Just Now');
        showAllMessages(chatList);
        let form = new FormData();
        form.append('msg', message ?? image);
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
                    $.ajax({
                        type: 'get',
                        url: "{{ route('admin.chats') }}",
                        data: {
                            search: $("#searchInput").val()
                        },
                        dataType: 'json',
                        success: function(result) {
                            if (result.status) {
                                let userData = result.data.html.data;
                                let html = result.data.html;
                                $("#appendData").html(result.data.html);
                            } else {
                                let html = `<div class="d-flex justify-content-center align-items-center flex-column">
                                            <div>
                                                <img width="250" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                                            </div>
                                        </div>`;
                                $("#appendData").html(html);
                            }
                        },
                        error: function(data, textStatus, errorThrown) {
                            jsonValue = jQuery.parseJSON(data.responseText);
                            console.error(jsonValue.message);
                        },
                    });
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
    $(document).on('click', '.user-info', function() {
        getClientChat(group_id, true);
        $(".unseen-count-"+receiver_id).remove();
        let form = new FormData();
        form.append('user_id', $("#ajax-chat-url").val());
        $.ajax({
            type: 'post',
            url: "{{ route('admin.chats.record.seen') }}",
            data: form,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status) {
                    // console.log(response.message);
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
        })
        
        $.ajax({
            type: 'get',
            url: "{{ route('admin.chats') }}",
            data: {
                search: $("#searchInput").val()
            },
            dataType: 'json',
            success: function(result) {
                if (result.status) {
                    let userData = result.data.html.data;
                    let html = result.data.html;
                    $("#appendData").html(result.data.html);
                } else {
                    let html = `<div class="d-flex justify-content-center align-items-center flex-column">
                                <div>
                                    <img width="250" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                                </div>
                            </div>`;
                    $("#appendData").html(html);
                }
            },
            error: function(data, textStatus, errorThrown) {
                jsonValue = jQuery.parseJSON(data.responseText);
                console.error(jsonValue.message);
            },
        });
    });
</script>

<script>
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
            let time = moment().format('MMM DD, YYYY HH:mm A');
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
        $('.messages-card').html('<div class="no-datafound-content">No messages found</div>');
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
        let msg = ` <div class="message-item  outgoing-message">
                         <div class="message-item-chat-card">
                            <div class="message-item-user">
                                <img src="{{ ((auth()->user()->profile=='' || auth()->user()->profile == null) && !file_exists(public_path('uploads/profile/'.auth()->user()->profile))) ? assets('assets/images/avatar.png') : assets('uploads/profile/'.auth()->user()->profile) }}" alt="avatar 1" >
                            </div>
                            <div class="message-item-chat-content">
                                <div class="message-content">
                                    ${(image !== undefined && image !== '') ? `<img style="border: 1px solid #eee; border-radius: 8px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;" src="${image}" alt="avatar"  width="100"/>` : ''}
                    ${(message !== '' && message !== undefined) ? `<p style="background: #1079c0;" class="small p-2 me-3 mb-1 text-white rounded-3">${message}</p>` : ''}
                                </div>
                                <div class="time">${time}</div>
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
        var formattedDate = moment.unix(row.createdAt.seconds).format('MMM DD, YYYY HH:mm A');
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
                                    ${(row.image !== undefined && row.image !== '') ? `<img style="border: 1px solid #eee; border-radius: 8px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;" src="${baseChatUrl+row.image}" alt="avatar"  width="100"/>` : ''}
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

    $(document).ready(function() {
        const getList = (search = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.chats') }}",
                data: {
                    search
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        let userData = result.data.html.data;
                        let html = result.data.html;
                        $("#appendData").html(result.data.html);
                    } else {
                        let html = `<div class="d-flex justify-content-center align-items-center flex-column">
                                    <div>
                                        <img width="250" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                                    </div>
                                </div>`;
                        $("#appendData").html(html);
                    }
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON(data.responseText);
                    console.error(jsonValue.message);
                },
            });
        };
        getList();
        $(document).on('keyup', "#searchInput", function() {
            let search = $("#searchInput").val();
            getList(search);
        });
    })
</script>

@endsection