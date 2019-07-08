import angular from 'angular';
export default angular.module('mainMenu',[]).factory('mainMenu', function(){
    var mainMenuService = {
        changeMenuState: false,
        openMainMenu: function(){
            this.changeMenuState = !this.changeMenuState;
        }
    };

    return mainMenuService;
}).controller('mainmenuController', ['$scope','mainMenu','$document', function($scope, mainMenu, $document){
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

    $scope.mainMenuService.changeMenuState = $scope.openMenu;
}]).controller('headController', ['$scope','mainMenu' ,function($scope,mainMenu){
    $scope.mainMenuService = mainMenu;
    $scope.openMenu = function(){
        mainMenu.openMainMenu();
    };
}]);