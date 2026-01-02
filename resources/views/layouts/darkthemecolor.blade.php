@php
// $color = App\Models\Utility::color();
if (isset($usr))
{
    $setting = \App\Models\Utility::settingsById($usr->id);
}
else {
    $setting = App\Models\Utility::colorset();
}
// $setting = App\Models\Utility::colorset();
$color = !empty($setting['color']) ? $setting['color'] : '#6fd943';
@endphp

<style>
    .application-offset .container-application:before {
        background-color: {{ $color }} !important;
    }

    .list-group-emphasized .list-group-item.active .media a {
        color: {{ $color }} !important;
    }

    /* a {
        color: {{ $color }} !important;
    } */

    .nav-application>.btn.active {
        background-color: {{ $color }} !important;
    }

    a:hover {
        color: {{ $color }} !important;
    }

    .btn-primary {

        background-color: {{ $color }} !important;
        border-color: {{ $color }} !important;
    }

    .btn-primary:hover {

        background-color: {{ $color }} !important;
        border-color: {{ $color }} !important;
    }

    .text-primary {
        color: {{ $color }} !important;
    }

    .custom-control-input:checked~.custom-control-label::before {
        border-color: {{ $color }} !important;
        background-color: {{ $color }} !important;
    }



    .navbar .dropdown-item:hover,
    .navbar .dropdown-item:focus {
        color: {{ $color }} !important;

    }

    .dropdown-item.active,
    .dropdown-item:active {
        color: {{ $color }} !important;

    }

    .bg-primary {
        background-color: {{ $color }} !important;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {

        background-color: {{ $color }} !important;
    }


    .btn-outline-primary {

    color:{{ $color }} !important;
    border-color: {{ $color }} !important;
}


    .btn-outline-primary:hover{

    color: #fff !important;
    background-color:{{ $color }} !important;
    border-color:{{ $color }} !important;

}


   .badge-primary {
    color: #fff;
    background-color:{{ $color }} !important;
}


    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    color: #FFF !important;
    background-color: {{ $color }} !important;
}



.btn-check:checked + .btn-outline-primary, .btn-check:active + .btn-outline-primary, .btn-outline-primary:active, .btn-outline-primary.active, .btn-outline-primary.dropdown-toggle.show {
    color: #ffffff !important;
    background-color: {{ $color }} !important;
    border-color: {{ $color }} !important;
}
</style>
