app.controller("RxWodEditCtrl", ['$scope', '$attrs', 'WodService', rxWodEditCtrl]);

function rxWodEditCtrl($scope, $attrs, WodService) {
    $scope.wodId = $attrs.wodId;
    $scope.wod = [];

    $scope.deleteWod = function (id) {
        console.log('deleteWod', id)
        WodService.deleteWod(id)
            .then(function() {
                //are you sure? check for score/lift records
                window.location = '../';
            });
    }

    $scope.editWod = function () {
        var data = $scope.wod;
        console.log(data)
        WodService.editWod($scope.wodId, data)
            .then(function() {
                //are you sure? check for score/lift records
                window.location = '../';
            });
    }

    
    $scope.getWod = function () {


        WodService.getWod($scope.wodId)
        .then(function (response) {
            $scope.wod = response.data;

            $scope.wod.createdAt = $scope.wod.createdAt.split('T')[0]
        });
    }

    $scope.getWod();

}
