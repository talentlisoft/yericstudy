@extends('layouts.adminbase')
@section('scripts')
    <script type="text/javascript" src="{{url(mix('/js/trainee.js'))}}"></script>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{url(mix('/css/trainee.css'))}}">
@endsection
@section('base')
    <base href="{{url('/trainee')}}/"/>
@endsection
@section('body')
@component('components.traineenav', ['title' => '学员主页', 'user' => $user])
    <li class="nav-item mr-2" ui-sref-active="{'active':'mytrain.**'}">
        <a class="nav-link" ui-sref="mytrain.mytrains.list">我的训练</a>
    </li>
@endcomponent
@endsection
