<!-- HEader -->
@php
$obj_user = '';
@endphp
@if( (Auth::guard('operator')->check() || Auth::guard('users')->check() ) && ( Request::segment(1) == 'operator' || Request::segment(1) == 'user' ) )
	@include('front.layout.inner_header')
@else
	@include('front.layout.header')
@endif
        
<!-- BEGIN Content -->
<div id="main-content">
    @yield('main_content')
</div>

<!-- END Main Content -->

<!-- Footer -->
@include('front.layout.footer')