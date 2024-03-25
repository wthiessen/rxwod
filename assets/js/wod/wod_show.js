app.controller("RxWodCtrl", ['$scope', '$attrs', '$http', 'WodService','LiftRecordService', 'LeaderboardService', rxWodCtrl]);

function rxWodCtrl ($scope, $attrs, $http, wodService, LiftRecordService, LeaderboardService) {
    $scope.loadedPage = 1;
    $scope.clickButton = false;
    $scope.lift_record = [];
    $scope.lift_history = [];
    $scope.editScore = false;
    $scope.wod = [];
    $scope.addForm = [];
    $scope.editLift = false;
    $scope.wodTypes = [];
    $scope.addScore = {};
    $scope.score = {};
    $scope.amrap = false;
    $scope.time = false;
    $scope.emom = false;
    $scope.addScore.commentsArray = [];

    $scope.wodHasLift = false;
    $scope.todaysWod = '';

    $scope.lift_record_from_wod = [];

    $scope.getLiftRecordFromWod = function() {
        LiftRecordService.getLiftDataFromWod($scope.wod.id)
            .then(function(response) {
                $scope.lift_record_from_wod = response.data;
            });
    }
    
    $scope.getWod = function () {
        wodService.getWod($attrs.wodId)
            .then(function (response) {
                $scope.wod = response.data;
            
                if (response.data.scores[0]) {
                    $scope.score = response.data.scores[0];
                }

                $scope.getLiftRecordsByWodFor();
                $scope.updateScore();

                $scope.scores = response.data.scores;
            });
    }

    $scope.getComment = function(value) {
        $scope.addScore.commentsArray.push(value);
        console.log(value, $scope.addScore.commentsArray)
        $scope.addScore.comments = $scope.addScore.commentsArray.join('\n');
    }

    $scope.getWod();

    $scope.updateScore = function() {
        $scope.amrap = $scope.wod.type == 'AMRAP';
        $scope.emom = $scope.wod.type == 'EMOM';

        if ($scope.wod.type == 'AMRAP') {
            $scope.addScore.score ={
                'rounds': null,
                'reps': null,
            }
        }

        if ($scope.wod.type == 'EMOM') {
            // $scope.addScore.score = $scope.wod.class[2][1];
        }

        $scope.time = $scope.wod.type == 'Time';

        if ($scope.wod.type == 'Time') {
            $scope.addScore.score ={
                'time': null,
            }
        }
    }

    $scope.updateScore();

    $scope.getScore = function(e, score) {
        e.stopPropagation();
        $scope.addScore = angular.copy(score);
        // $scope.addScore.score = $scope.wod.class[2][1];
    }

    $scope.addScoreFunc = function () {

        if ($scope.addScore.id) {
            if ($scope.wod.type =- 'EMOM') {
                // $scope.addScore.score = 'EMOM';
            }

            var data = {
                id: $scope.addScore.id,
                wod_id: parseInt($attrs.wodId),
                user_id: 6, // TODO update get user
                score: JSON.stringify($scope.addScore.score),
                comments: $scope.addScore.comments,
            }

            LeaderboardService.editScore(data)
            .then(function (response) {
                $scope.addScore = {};
                $scope.getWod();
            });

            return;
        }

        var data = {
            wod_id: parseInt($attrs.wodId),
            user_id: 6, // TODO update get user
            score: JSON.stringify($scope.addScore.score),
            comments: $scope.addScore.comments,
        }

        LeaderboardService.addScore(data)
            .then(function (response) {
                $scope.addScore = {};
                $scope.getWod();
            });
    }

    $scope.clear = function() {
        $scope.addScore = {};
    }
    
    $scope.editWodScore = function () {
        var data = {
            id: $scope.score.id,
            wod_id: parseInt($attrs.wodId),
            user_id: 6, // TODO update get user
            score: JSON.stringify($scope.score.score),
            comments: $scope.score.comments,
        }

        LeaderboardService.editScore(data)
            .then(function() {
                $scope.getWod();
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
        LiftRecordService.getLiftRecordsByWodFor($attrs.wodId)
            .then(function(response) {
                $scope.lift_record = response.data[0];
                if (!$scope.lift_record) {
                    if ($scope.wod.class[1]) {
                        $scope.lift_record_from_wod.exercise = angular.copy($scope.wod.class[1][1]);
                        $scope.lift_record_from_wod.repScheme = angular.copy($scope.wod.class[1][2]);
                        var skill = angular.copy($scope.wod.class[1]);
                        if (skill) {
                            console.log(skill)
                            // skill.splice(0, 1);
                            // skill.splice(0, 1);
                            // skill.splice(0, 1);
                            // $scope.lift_record_from_wod.comment = skill.join('\n');
                        }

                        $scope.lift_record_from_wod.weight = null;
                    }
                }
                $scope.lift_update = angular.copy($scope.lift_record);
            });
    }

    $scope.showScoreEdit = false;

    $scope.showLiftScoreEdit = function () {
        $scope.showScoreEdit = !$scope.showScoreEdit;
    }

    $scope.toggleLiftEdit = function() {
        $scope.editLift = !$scope.editLift;
    }

    $scope.editLiftScore = function () {
        var data = {
            wod_id: parseInt($scope.wod.id),
            exercise: $scope.lift_record_from_wod.exercise,
            rep_scheme: $scope.lift_record_from_wod.repScheme,
            comment: $scope.lift_record_from_wod.comment,
            weight: parseInt($scope.lift_update.weight),
            id: parseInt($scope.lift_record.id),
        }

        LiftRecordService.editLiftRecord(data)
            .then(function() {
                $scope.toggleLiftEdit();
                $scope.getLiftRecordsByWodFor($attrs.wodId);
            });
    }

    $scope.addLift = function() {
        $scope.lift_record_from_wod.wodId = $scope.wod.id;
        
        var data = {
            wod_id: parseInt($scope.wod.id),
            exercise: $scope.lift_record_from_wod.exercise,
            rep_scheme: $scope.lift_record_from_wod.repScheme,
            comment: $scope.lift_record_from_wod.comment,
            weight: null
        }

        LiftRecordService.addLiftRecord(data)
            .then(function(response) {
                $scope.lift_records = response.data;
                $scope.getLiftRecordsByWodFor();
                console.log('saved lift')
            });
    }

    $scope.getWodTypes = function() {
        wodService.getWodTypes()
        .then(function(response) {
            // console.log(response);
            $scope.wodTypes = response.data;
            // $scope.addForm.type = 'AMRAP';
        })
    }

    $scope.getWodTypes();

}
