export default angular.module('yericfilter',[]).filter('yericfomular', function() {
    return function(text) {
        return text.replace(/\//g, '÷').replace(/(\D)(\d{1,})/g,'$1 $2').replace(/(\d{1,})(\D)/g,'$1 $2').replace(/\*/g, '×');
    };
});