var app = angular.module("myApp", [])
.filter('trustAsHtml', ['$sce', function($sce){
    return function(val) {
        return $sce.trustAsHtml(val);
    };
}])
.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('{:').endSymbol(':}');
});
