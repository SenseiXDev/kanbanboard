@extends('layouts.admin')

@include('Chatify::layouts.headLinks')

@section('title')
    {{__('Messenger')}}
@endsection


@php
//  $color = Cookie::get('theme_color');

$profile=\App\Models\Utility::get_file('/'.config('chatify.user_avatar.folder'));  
$setting = App\Models\Utility::colorset();
$color = (!empty($setting['color'])) ? $setting['color'] : '#6fd943';
if (!isset($color)) {
    $color = '#6fd943';
}
@endphp

@section('content')
<div class="messenger rounded min-h-750 overflow-hidden mt-4">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav>
                {{-- <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">MESSAGES</span> </a> --}}
                {{-- header buttons --}}
                <nav class="m-header-right">
                    {{-- <a href="#"><i class="fas fa-cog settings-btn"></i></a> --}}
                    <a href="#" class="listView-x" ><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            {{-- Search input --}}
            <input type="text" class="messenger-search" placeholder="Search" />
            {{-- Tabs --}}
            <div class="messenger-listView-tabs">
                <a href="#" @if($route == 'user') class="active-tab" @endif data-view="users">
                    <span class="fa fa-clock"></span></a>
                <a href="#" @if($route == 'group') class="active-tab" @endif data-view="groups">
                    <span class="fas fa-users"></span></a>
            </div>
        </div>
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
           {{-- Lists [Users/Group] --}}
           {{-- ---------------- [ User Tab ] ---------------- --}}
           <div class="@if($route == 'user') show @endif messenger-tab users-tab app-scroll" data-view="users">

               {{-- Favorites --}}
               <div class="favorites-section">
                <p class="messenger-title">Favorites</p>
                <div class="messenger-favorites app-scroll-thin"></div>
               </div>

               {{-- Saved Messages --}}
               {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}

               {{-- Contact --}}
               <div class="listOfContacts" style="width: 100%;height: calc(100% - 200px);position: relative;"></div>

           </div>

           {{-- ---------------- [ Group Tab ] ---------------- --}}
           {{--  <div class="@if($route == 'user') show @endif messenger-tab groups-tab app-scroll" data-view="groups">
                
                <p style="text-align: center;color:grey;margin-top:30px">
                    <a target="_blank" style="color:{{$messengerColor}};"></a> 
                </p>
             </div>  --}}
             <div class="all_members @if($route == 'group') show @endif messenger-tab app-scroll" data-view="groups">
                <p style="text-align: center;color:grey;">{{__('Soon will be available')}}</p>
            </div>

             {{-- ---------------- [ Search Tab ] ---------------- --}}
           <div class="messenger-tab search-tab app-scroll" data-view="search">
                {{-- items --}}
                <p class="messenger-title">Search</p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
             </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] amd buttons --}}
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                {{-- header back button, avatar and user name --}}
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px; background-image: url('{{$profile.'/avatars/avatar.png'}}');">
                    </div>
                    <a href="#" class="user-name">{{ config('chatify.name') }}</a>
                </div>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    {{-- <a href="/"><i class="fas fa-home"></i></a> --}}
                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                </nav>
            </nav>
        </div>
        {{-- Internet connection --}}
        <div class="internet-connection">
            <span class="ic-connected">{{__('Connected')}}</span>
                <span class="ic-connecting">{{__('Connecting...')}}</span>
                <span class="ic-noInternet">{{__('Please add pusher settings for using messenger.')}}</span>
        </div>
        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <p>
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </p>
                </div>
            </div>
            {{-- Send Message Form --}}
            @include('Chatify::layouts.sendForm')
        </div>
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    <div class="messenger-infoView app-scroll">
        {{-- nav actions --}}
        <nav style="margin-left: 90px;">
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
</div>
@endsection
@push('script')

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')


<script>
    $("#drop_a").click(function(){
        $("drop_a").attr('aria-expanded');
        $("#drop_li").addClass("show");
        $("#drop_div").addClass("show");
    });
</script>

@endpush

<style type="text/css">
    .m-list-active, .m-list-active:hover, .m-list-active:focus {
    background: {{$color}} !important;
}
.mc-sender p {
    background: {{$color}} !important;
}

.messenger-favorites div.avatar {
    box-shadow: 0px 0px 0px 2px {{$color}} !important;
}
.messenger-listView-tabs a, .messenger-listView-tabs a:hover, .messenger-listView-tabs a:focus {
    color: {{$color}} !important;
}
.m-header svg {
    color: {{$color}} !important;
}
.active-tab {
    border-bottom: 2px solid {{$color}} !important;
}
.messenger-infoView nav a {
  
    color: {{$color}} !important;
}


.messenger-list-item td span .lastMessageIndicator {

    color: {{$color}} !important;
    font-weight: bold;
}
.messenger-sendCard button svg {
     color: {{$color}} !important;
}


.mc-sender p sub {
    color: #fff !important;
}

.mc-sender p {
    direction: ltr;
    color: #fff !important;
}

.m-list-active  td span .lastMessageIndicator{
     color: #fff !important;
}


.messenger-list-item td b {
    
    background-color:  {{$color}} !important;
}

</style>

