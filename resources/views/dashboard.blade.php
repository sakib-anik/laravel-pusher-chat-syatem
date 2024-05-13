@extends('layouts.app')
@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
@endsection

@section('content')
<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <section style="background-color: #eee;">
                        <div class="container py-5">

                            <div class="row d-flex justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-4">

                                <div class="card" id="chat1" style="border-radius: 15px;">
                                <div
                                    class="card-header d-flex justify-content-between align-items-center p-3 bg-info text-white border-bottom-0"
                                    style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                    <i class="fas fa-angle-left"></i>
                                    <p class="mb-0 fw-bold">Live chat</p>
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="card-body">
                                    <div id="chat-body">

                                    </div>






                                    <input type="hidden" name="receiver_id" id="receiver_id" value="{{ auth()->id() == 1 ? 2 : 1 }}">
                                    <div data-mdb-input-init class="form-outline">
                                    <textarea class="form-control" id="message" rows="4" placeholder="Type Your Message"></textarea>
                                    <div class="" style="text-align: right" class="mt-2">
                                        <button id="sendBtn" class="btn btn-info btn-sm mt-2">Send</button>
                                    </div>
                                    </div>

                                </div>
                                </div>

                            </div>
                            </div>

                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    @vite('resources/js/app.js')
    <script type="module">
        Echo.channel('chat-room')
        .listen('MessageSent', (e) => {
            if(e.message != null){
                let userId = "{{ auth()->id() }}";
                if(e.message.sender_id == userId){
                    $('#chat-body').append(`
                    <div class="d-flex flex-row justify-content-start mb-4">
                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp"
                            alt="avatar 1" style="width: 45px; height: 100%;">
                        <div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2);">
                            <p class="small mb-0">${e.message.message}</p>
                        </div>
                    </div>
                `);
                }
                if(e.message.receiver_id == userId){
                    $('#chat-body').append(`
                    <div class="d-flex flex-row justify-content-end mb-4">
                        <div class="p-3 me-3 border" style="border-radius: 15px; background-color: #fbfbfb;">
                            <p class="small mb-0">${e.message.message}</p>
                        </div>
                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava2-bg.webp"
                            alt="avatar 1" style="width: 45px; height: 100%;">
                    </div>
                `);
                }

            }
            console.log(e.message);
        });
    </script>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#sendBtn').click(function(){
                let message = $('#message').val();
                let receiver_id = $('#receiver_id').val();

                $.ajax({
                    url: "{{ route('send-message') }}",
                    method: 'POST',
                    data: {message, receiver_id},
                    success: function(response){
                        // console.log(response);
                        $('#message').val('');
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            });
        })
    </script>
@endsection

