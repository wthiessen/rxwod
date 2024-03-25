app.controller("rxLiftRecordCtrl", ['$scope', rxLiftRecordCtrl]);

function rxLiftRecordCtrl($scope) {
    $scope.newLiftRecord = [];

    $scope.getLiftRecords = function() {
        LiftService.getLiftRecords($scope.loadedPage)
            .then(function(response) {
                // console.log(response)
                $scope.lift_records = response.data;
            });
    }

    $scope.addLift = function() {
        LiftService.addLiftRecord($scope.newLiftRecord)
            .then(function(response) {
                // console.log(response)
                // $scope.lift_records = response.data;
            });
    }

    // newLiftRecord
    $scope.getLiftRecords();
}
