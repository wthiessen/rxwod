app.controller("RxWodEditCtrl", ['$scope', 'WodService', rxWodEditCtrl]);

function rxWodEditCtrl($scope, WodService) {
    // console.log('rxWodEditCtrl')
    $scope.deleteWod = function (id) {
        console.log('deleteWod', id)
        WodService.deleteWod(id)
            .then(function() {
                //are you sure? check for score/lift records
                window.location = '../';
            });
    }
}
