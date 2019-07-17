import angular from 'angular';
import uirouter from '@uirouter/angularjs';
export default angular.module('mainMenu',['ui.router']).factory('mainMenu', function(){
    var mainMenuService = {
        changeMenuState: false,
        openMainMenu: function(){
            this.changeMenuState = !this.changeMenuState;
        }
    };

    return mainMenuService;
}).controller('mainmenuController', ['$scope','mainMenu','$document', '$transitions', function($scope, mainMenu, $document, $transitions){
    $scope.mainMenuService = mainMenu;
    $scope.openMenu = function(){
        mainMenu.openMainMenu();
    };

    $document.bind('touchmove', function(event){
        if ($scope.showMenu &&　event.cancelable){
            event.preventDefault();
        }
        return !$scope.showMenu;
    });

    $transitions.onSuccess({}, function(transition) {
        mainMenu.changeMenuState = true;
    });

    $scope.mainMenuService.changeMenuState = $scope.openMenu;
}]).controller('headController', ['$scope','mainMenu', '$transitions', function($scope,mainMenu, $transitions){
    $scope.mainMenuService = mainMenu;
    $scope.isNavCollapsed = false;
    $scope.openMenu = function(){
        mainMenu.openMainMenu();
    };
    $transitions.onSuccess({}, function(transition) {
        $scope.isNavCollapsed = false;
    });
}]);