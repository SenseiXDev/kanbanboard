
@php
$logo   = \App\Models\Utility::get_file('/');
$avatar = \App\Models\Utility::get_file('/avatars/');
@endphp
<div class="favorite-list-item">
    @if(!empty($user->avatar))
        <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m"
              style="background-image: url('{{$logo.$user->avatar}}');">
        </div>
    @else
        <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m"
             style="background-image: url('{{$avatar.'avatar.png'}}');">
        </div>
    @endif
    <p>{{ strlen($user->name) > 5 ? substr($user->name,0,6).'..' : $user->name }}</p>
</div>
