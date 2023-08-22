app.controller("RxWodCtrl", function($scope, $attrs, $http) {
    $scope.loadedPage = 1;
    $scope.clickButton = false;
    $scope.lift_record = [];
    $scope.lift_history = [];
    $scope.editScore = false;
    $scope.wod = [];
    $scope.addForm = [];

    $scope.wodHasLift = false;
    $scope.todaysWod = '';

    $scope.getLiftRecordsFor = function () {
        var text = $scope.wod.wod;
        text = text.split('<br/><br/>')

        var ex = '';
        if (text) {
        if (text[1]) {
            ex = text[1].split('<br/>') || '';
        }

        if (ex[0]) {
            $scope.wodHasLift = ex[0].includes('Lift');
        }
    }

    if ($scope.wodHasLift) {
        $http({
            url: '../api/lift_records.json?exercise=' + ex[1],
            method: 'GET',
        }).then(function(response) {
            console.log(response)
            $scope.lift_history = response.data;
        });
        }
    }

    $scope.getWod = function () {
        $http({
            url: '../api/wods/' + $attrs.wodId,
            method: 'GET',
        }).then(function (response) {
            $scope.wod = response.data;

            var text = $scope.wod.wod;

            text = text.split('<br/><br/>')

            if (text && !$scope.scores) {
            text = text[2] || '';

            if (text) {
                text = text.split('Daily Task')[1]
            }

            if (text) {
                text = text.split('<br/>')
            }

            if (text) {
                text = text.filter(item => item);

                if (text[0].toLowerCase().includes('emom') || text[0].toLowerCase().includes('amrap')) {
                    $scope.addForm.score = text[0].toUpperCase();
                    text.splice(0, 1)
                }


                $scope.addForm.comments = text.join('\n');
            }
        }

            // console.log(text);
            $scope.getLeaderboard();
            $scope.getLiftRecordsFor();
        });
    }

    $scope.getWod();

    $scope.score = {};

    $scope.getLeaderboard = function () {
        $http({
            url: '../api/leaderboards.json?wod=' + $attrs.wodId,
            method: 'GET',
        }).then(function (response) {
            $scope.getLiftRecordsByWodFor();

            // if (response.data) {
            //     $scope.addForm.comments = $scope.scores[0];
            // }

            $scope.scores = response.data;
        });
    }

    $scope.addScore = function () {
        // score.wod = $attrs.wodId

        var data = {
            wod: parseInt($attrs.wodId),
            name: 'Will',//score.name,
            score: $scope.addForm.score,
            comments: $scope.addForm.comments,
        }

        $http({
            url: '../api/leaderboards',
            data: data,
            method: 'POST',
        }).then(function (response) {
            $scope.getLeaderboard();
        });
    }

    $scope.editWodScore = function () {
        var data = {
            score: $scope.addForm.score,
            comments: $scope.addForm.comments,
        }

        $http({
            url: '../api/leaderboards/' + $scope.scores[0].id,
            data: data,
            method: 'PUT',
        }).then(function(response) {
            $scope.getLeaderboard();
            $scope.editScore = false;
        });
    }

    $scope.showLifts = false;
    $scope.enterScore = false;

    $scope.liftRecords = function () {
        $scope.showLifts = !$scope.showLifts;
        $scope.showNewScore = false;
    }

    $scope.enterScore = function () {
        $scope.showNewScore = !$scope.showNewScore;
        $scope.showLifts = false;
    }

    $scope.getLiftRecordsByWodFor = function () {
        $http({
            url: '../api/lift_records.json?wodId=' + $attrs.wodId,
            method: 'GET',
        }).then(function(response) {
            $scope.lift_record = response.data;
        });
    }

    $scope.showScoreEdit = false;

    $scope.showLiftScoreEdit = function () {
        $scope.showScoreEdit = !$scope.showScoreEdit;
    }

    $scope.addLiftScore = function () {
        var data = {
            weight: parseInt($scope.lift_record[0].weight),
            wodId: parseInt($attrs.wodId),
        }

        $http({
            url: '../../api/lift_records/' + $scope.lift_history[0].id,
            data: data,
            method: 'PUT',
        }).then(function (response) {
            $scope.getLiftRecordsByWodFor();
        });
    }
});
