@extends('layouts.adminvuebase')
@section('scripts')
    <script type="text/javascript" src="{{url(mix('/js/vue.js'))}}"></script>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{url(mix('/css/adminvue.css'))}}">
@endsection
@section('base')
    <base href="{{url('/admin')}}/"/>
@endsection
@section('body')
    <div id="studyapp">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top crm-nav">
            <div class="navbar-brand navbar-left">
                YericStudy
                <button id="submenutoggle" class="btn btn-sm fl-right btn-dark float-right slideBtn">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <button class="navbar-toggler" id="togglemainmenu" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="nav-content navbar-collapse collapse">
                <ul class="navbar-nav mr-auto pl-3 text-left">
                    <li class="nav-item mr-2">
                        <router-link class="nav-link" active-class="active" to="/system/topicssummary">系统配置</router-link>
                    </li>
                    <li class="nav-item d-sm-block d-md-block d-lg-none">
                        <a href onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出</a>
                    </li>
                </ul>
                <div class="navbar-text my-2 my-lg-0 d-none d-lg-block">
                    <span>
                        <span id="usermenu">{{$user['name']}}</span>
                        <div class="dropdown-menu dropdown-menu-right bg-dark" role="menu">
                            <a href class="dropdown-item"><i class="fa fa-key" aria-hidden="true"></i> 更改密码...</a>
                            <div class="dropdown-divider"></div>
                            <a href onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出</a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </span>
                </div>
            </div>
        </nav>
        <div class="crm-content">
            <router-view></router-view>
        </div>
        
    </div>
@endsection
