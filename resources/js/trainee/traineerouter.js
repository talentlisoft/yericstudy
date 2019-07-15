import traineeModule from './traineemodule';

export default traineeModule.config(['$locationProvider', function ($locationProvider) {
    $locationProvider.html5Mode(true);
}]);