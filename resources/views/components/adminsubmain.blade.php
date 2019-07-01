<div ng-controller="mainmenuController">
    <div class="sidebar-nav d-print-none" ng-class="!mainMenuService.changeMenuState?'sidebar-nav-show':'sidebar-nav-hide'" id="sidebar" ng-cloak>
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                {{ $slot }}
            </ul>
        </div>
    </div>
    <div class="maincontent" ng-class="!mainMenuService.changeMenuState?'maincontent-offset':'maincontent-noOffset'" id="maincontent">
        <ui-view class="w-100 d-block container-fluid uiview"></ui-view>
    </div>
</div>
