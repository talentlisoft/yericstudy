export default angular.module('yericfilter',[]).filter('yericfomular', function() {
    return function(text) {
        return text.replace(/(\d{1,}\)?)?\s*\/\s*(\(?\d{1,})/g, '$1 ÷ $2').replace(/(\d{1,}\)?)?\s*\*\s*(\(?\d{1,})/g, '$1 × $2').replace(/(\d{1,}\)?)?\s*(\-|\+)\s*(\(?\d{1,})/g, '$1 $2 $3');
    };
});