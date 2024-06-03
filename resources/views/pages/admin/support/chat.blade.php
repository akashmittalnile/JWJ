@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/help.css') }}">
@endpush

@section('title','Journey with Journals - Chat')
@section('content')
<meta name="_token" content="{{csrf_token()}}" />
<div class="body-main-content">
    <div class="message-section">
        <section style="background-color: #e6e6e6; border-radius: 30px;">
            <div class="container p-4">

                <div class="row">
                    <div class="col-md-12">

                        <div class="card" id="chat3" style="border-radius: 15px;">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">

                                        <div class="p-3">

                                            <!-- <div class="input-group rounded mb-3">
                                                <input type="text" class="form-control rounded border me-2" placeholder="Search" aria-label="Search" aria-describedby="search-addon" id="myinput" />
                                                <span class="input-group-text border-0" id="search-addon" style="background: #1079c0;">
                                                    <i class="las la-search"></i>
                                                </span>
                                            </div> -->

                                            <div class="search-filter wd9 mb-3">
                                                <div class="row g-1">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="search-form-group">
                                                                <input type="text" name="" id="searchInput" class="form-control" placeholder="Search by user name & email address">
                                                                <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px; overflow-y: scroll;">
                                                <ul class="list-unstyled mb-0" id="appendData">

                                                    

                                                </ul>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6 col-lg-7 col-xl-8 body-chat-message-user d-none">

                                        <div class="pt-3 pe-3 messages-card" data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px; overflow-y: scroll;">



                                        </div>

                                        <div class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2">
                                            <div>
                                                <img style="border-radius: 50%; object-fit: cover; object-position: center;" src="{{ assets('assets/images/user.svg') }}" alt="avatar" class="d-flex align-self-center me-3" width="60" height="60" id="userAvatar">
                                            </div>

                                            <input type="text" class="form-control-input form-control-lg border ms-3" id="message-input" placeholder="Type message">

                                            <a class="fs-24 ms-3 text-muted" id="image-attach" href="javascript:void(0)"><i class="las la-paperclip"></i></a>

                                            <input type="file" hidden accept="image/png, image/jpg, image/jpeg" id="upload-file" name="image-attachment">

                                            <!-- <a class="fs-24 ms-3 text-muted" href="javascript:void(0)"><i class="las la-smile"></i></a> -->
                                            <a class="fs-24 ms-3" href="javascript:void(0)"><i class="las la-paper-plane btnSend"></i></a>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <input type="hidden" id="ajax-chat-url" value="">
                        <input type="hidden" id="ajax-chat-url-name" value="">
                        <input type="hidden" id="ajax-chat-url-img" value="">
                    </div>
                </div>

            </div>
        </section>
    </div>
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
        $('.messages-card').html('<div style="margin-top: 25%; font-size: 1rem;" class="d-flex align-items-center justify-content-center">No messages found</div>');
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
        let msg = `<div class="d-flex flex-row justify-content-end">
                <div>
                    ${(image !== undefined && image !== '') ? `<img style="border: 1px solid #eee; border-radius: 8px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;" src="${image}" alt="avatar" class="d-flex align-self-center m-3" width="100"/>` : ''}
                    ${(message !== '' && message !== undefined) ? `<p style="background: #1079c0;" class="small p-2 me-3 mb-1 text-white rounded-3">${message}</p>` : ''}
                    <p class="small me-3 mb-3 rounded-3 text-muted">${time}</p>
                </div>
                <img src="{{ ((auth()->user()->profile=='' || auth()->user()->profile == null) && !file_exists(public_path('uploads/profile/'.auth()->user()->profile))) ? assets('assets/images/avatar.png') : assets('uploads/profile/'.auth()->user()->profile) }}" alt="avatar 1" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; object-position: center;">
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

            html = `<div class="d-flex flex-row justify-content-start">
                    <img style="border-radius: 50%; object-fit: cover; object-position: center;" src="${userProImg}" alt="avatar" class="d-flex align-self-center me-3" width="60" height="60">
                    <div>
                        ${(row.image !== undefined && row.image !== '') ? `<img style="border: 1px solid #eee; border-radius: 8px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;" src="${baseChatUrl+row.image}" alt="avatar" class="d-flex align-self-center m-3" width="100"/>` : ''}
                        ${(row.text !== '' && row.text !== undefined) ? `<p style="background: #9fc9e6;" class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${row.text}</p>` : '' }
                        <p class="small ms-3 mb-3 rounded-3 text-muted float-end">${formattedDate}</p>
                    </div>
                </div>`;
        } else {
            html = `<div class="d-flex flex-row justify-content-end">
                <div>
                    ${(row.image !== undefined && row.image !== '') ? `<img style="border: 1px solid #eee; border-radius: 8px; box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;" src="${baseChatUrl+row.image}" alt="avatar" class="d-flex align-self-center m-3" width="100"/>` : ''}
                    ${(row.text !== '' && row.text !== undefined) ? `<p style="background: #1079c0;" class="small p-2 me-3 mb-1 text-white rounded-3">${row.text}</p>` : '' }
                    <p class="small ms-3 mb-3 rounded-3 text-muted">${formattedDate}</p>
                </div>
                <img src="{{ ((auth()->user()->profile=='' || auth()->user()->profile == null) && !file_exists(public_path('uploads/profile/'.auth()->user()->profile))) ? assets('assets/images/avatar.png') : assets('uploads/profile/'.auth()->user()->profile) }}" alt="avatar 1" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; object-position: center;">
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