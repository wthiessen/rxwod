app.controller("RxWodEditCtrl", ['$scope', '$attrs', 'WodService', rxWodEditCtrl]);

function rxWodEditCtrl($scope, $attrs, WodService) {
    $scope.wodId = $attrs.wodId;
    $scope.wod = [];

    $scope.deleteWod = function () {
        WodService.deleteWod($scope.wodId)
            .then(function() {
                //are you sure? check for score/lift records
                window.location = '../';
            });
    }

    $scope.editWod = function () {
        var data = $scope.wod;

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
            $scope.wod.wod = $scope.wod.wod.replaceAll("<br />", '');
            $scope.wod.wod = $scope.wod.wod.replaceAll("<br/>", '\n');
            $scope.wod.createdAt = new Date($scope.wod.createdAt);
        });
    }

    $scope.getWod();
}
