import traineeModule from './traineemodule';

export default traineeModule.factory('Persist', [function() {
    return {
        mytrain: {
            list: {
                total: 0,
                currentPage: 1,
                scope: 'PENDDING'
            }
        }
    }
}]);