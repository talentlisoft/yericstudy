<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top crm-nav" ng-controller="headController">
    <div class="navbar-brand navbar-left">
        {{$title}}
        <button id="submenutoggle" class="btn btn-sm fl-right btn-dark float-right slideBtn" ng-click="openMenu()">
            <i class="fa fa-bars"></i>
        </button>
    </div>
    <button class="navbar-toggler collapsed" type="button" ng-click="isNavCollapsed = !isNavCollapsed">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="nav-content navbar-collapse collapse" uib-collapse="!isNavCollapsed">
        <ul class="navbar-nav mr-auto pl-3 text-left">
            {{$slot}}
            <li class="nav-item d-sm-block d-md-block d-lg-none">
                <a href onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出</a>
            </li>
        </ul>
        <div class="navbar-text my-2 my-lg-0 d-none d-lg-block">
            <span uib-dropdown>
                <span uib-dropdown-toggle id="usermenu">{{$user['name']}}</span>
                <div class="dropdown-menu dropdown-menu-right bg-dark" uib-dropdown-menu role="menu">
                    <a href toggle-passdialog class="dropdown-item"><i class="fa fa-key" aria-hidden="true"></i> 更改密码...</a>
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
    <ui-view class="d-block mainuiview"></ui-view>
</div>
