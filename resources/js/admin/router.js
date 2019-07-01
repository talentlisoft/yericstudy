import appmodule from './appmodule';

export default appmodule.config(['$stateProvider', '$locationProvider', function ($stateProvider, $locationProvider) {
    $locationProvider.html5Mode(true);
}]);