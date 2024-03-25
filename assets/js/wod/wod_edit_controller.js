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

        data.wodDate = $scope.wod.wodDate.toLocaleDateString();

        // console.log(data.wodDate); return;
        WodService.editWod($scope.wodId, data)
            .then(function(response) {
                //are you sure? check for score/lift records
                window.location = '/wod/' + $scope.wodId;
            });
    }

    $scope.wodTypes = ['AMRAP', 'EMOM', 'Time'];

    $scope.getWod = function () {
        WodService.getWod($scope.wodId)
        .then(function (response) {
            $scope.wod = response.data;
            $scope.wod.wod = $scope.wod.wod.replaceAll("<br />", '');
            $scope.wod.wod = $scope.wod.wod.replaceAll("<br/>", '\n');
            // $scope.wod.createdAt = new Date($scope.wod.createdAt);
            var date = new Date($scope.wod.wodDate);
            $scope.wod.wodDate = new Date(date.getTime() + 86400000);

            // console.log($scope.wod.wodDateFormat)
            // $scope.wod.updatedOn = $scope.wod.updatedOn ? new Date($scope.wod.updatedOn) : null;
        });
    }

    $scope.getWod();
}
