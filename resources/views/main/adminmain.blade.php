@extends('layouts.adminbase')
@section('scripts')
    <script type="text/javascript" src="{{url(mix('/js/admin.js'))}}"></script>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{url(mix('/css/admin.css'))}}">
@endsection
@section('base')
    <base href="{{url('/admin')}}/"/>
@endsection
@section('body')
@component('components.adminnav', ['title' => ' 管理后台', 'user' => $user])
    <li class="nav-item mr-2" ui-sref-active="{'active':'system.**'}">
        <a class="nav-link" ui-sref="system.topics.summary">系统配置</a>
    </li>
@endcomponent
@endsection
