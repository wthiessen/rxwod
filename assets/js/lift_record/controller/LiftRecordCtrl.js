app.controller("rxLiftRecordCtrl", ['LiftService', '$scope', rxLiftRecordCtrl]);

function rxLiftRecordCtrl(LiftService, $scope) {
    $scope.newLiftRecord = [];

    $scope.getLiftRecords = function() {
        LiftService.getLiftRecords($scope.loadedPage)
            .then(function(response) {
                // console.log(response)
                $scope.lift_records = response.data;
            });
    }

    $scope.addLiftRecord = function() {
        LiftService.addLiftRecord($scope.newLiftRecord)
            .then(function(response) {
                // console.log(response)
                // $scope.lift_records = response.data;
            });
    }

    // newLiftRecord
    $scope.getLiftRecords();
}
