<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top crm-nav" ng-controller="headController">
    <div class="navbar-brand navbar-left">
        {{$title}}
        <button id="submenutoggle" class="btn btn-sm fl-right btn-dark float-right slideBtn" ng-click="openMenu()">
            <i class="fa fa-bars"></i>
        </button>
    </div>
    <button class="navbar-toggler collapsed" id="togglemainmenu" type="button" ng-click="isNavCollapsed = !isNavCollapsed">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="nav-content navbar-collapse collapse" uib-collapse="!isNavCollapsed" click-outside="isNavCollapsed = false" outside-if-not="togglemainmenu">
        <ul class="navbar-nav mr-auto pl-3 text-left">
            {{$slot}}
            <li class="nav-item d-sm-block d-md-block d-lg-none">
                <a href="{{url('traineeauth/logout')}}" class="nav-link"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出</a>
            </li>
        </ul>
        <div class="navbar-text my-2 my-lg-0 d-none d-lg-block">
            <a class="navbar-brand traineeavatar">
                <img src="{{$user['avatar']}}" alt="">
            </a>
                
            
            <span uib-dropdown>
                <span uib-dropdown-toggle id="usermenu">{{$user['name']}}</span>
                <div class="dropdown-menu dropdown-menu-right bg-dark" uib-dropdown-menu role="menu">
                    <div class="dropdown-divider"></div>
                    <a href="{{url('traineeauth/logout')}}" class="dropdown-item"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出</a>
                </div>
            </span>
        </div>
    </div>
</nav>
<div class="crm-content">
    <ui-view class="d-block mainuiview"></ui-view>
</div>
