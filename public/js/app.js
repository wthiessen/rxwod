/** rxwod 2023 **/
'use strict';

(function() {





var app = angular.module("myApp", [])
.filter('trustAsHtml', ['$sce', function($sce){
    return function(val) {
        return $sce.trustAsHtml(val);
    };
}])
.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('{:').endSymbol(':}');
});

'use strict';

(function() {





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

app.service('LiftService', ['$http', LiftService]);

function LiftService($http)
{
    this.baseUrl = 'api/lift_records';

    this.getLiftRecords = function (page) {
        return $http({
            url: this.baseUrl,
            method: 'GET',
        });
    };

    this.getLiftRecord = function (id) {
        return $http({
            url: this.baseUrl + '/' + id,
            method: 'GET',
        });
    };

    this.addLiftRecord = function (data) {
        return $http({
            url: this.baseUrl,
            data: data,
            method: 'POST',
        });
    };
}

})();

app.service('WodService', ['$http', WodService]);

function WodService($http)
{
    this.baseUrl = 'api/wods';

    this.getWods = function (page) {
        return $http({
            url: this.baseUrl + '.json?page=' + page,
            method: 'GET',
        });
    };

    this.getWodUrl = function (id) {
        return 'wod/' + id;
    };

    this.deleteWod = function (id) {
        return $http({
            url: '../../' + this.baseUrl + '/' + id,
            method: 'DELETE',
        });
    }

    this.editWod = function (id, data) {
        return $http({
            url: '../../' + this.baseUrl + '/' + id,
            data: data,
            method: 'PUT',
        });
    }

    this.addWod = function (data) {
        return $http({
            url: '../../' + this.baseUrl,
            data: data,
            method: 'POST',
        });
    }

    this.getWod = function (id) {
        return $http({
            url: '../../' + this.baseUrl + '/' + id,
            method: 'GET',
        });
    }
}

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

app.controller("rxWodImportCtrl", ['$scope', '$http', '$attrs', wodImportCtrl]);

function wodImportCtrl($scope, $http, $attrs) {
    $scope.content = '';
    $scope.created = '';

console.log($attrs.glofoxUrl)
    $http({
        url: $attrs.glofoxUrl,
        method: 'GET',
        headers: {
            Authorization: 'Bearer ' + $attrs.token,
        }
    }).then(function (response) {
        var content = response.data.data[0].content;

        var date = new Date(((response.data.data[0].created * 1000) + (24 * 60 * 60)));
        var created = new Date(date.getTime() - (date.getTimezoneOffset() * 60000 ))
            .toISOString()
            .split("T")[0];
        // console.log(created);

        $scope.created = created;
        $scope.content = $scope.content.trim();
        $scope.content = content.split('Matt</p><p>')[1];
        // $scope.content = $scope.content.replaceAll("<br/>", '\n');
        // $scope.content = $scope.content.replace("\n", '');
        $scope.content = $scope.content.replace(/<[^>]*>?/gm, '');
        $scope.content = $scope.content.split('See you all for a fun filled weekend workout!!')[0];
    });
}


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
            var wodParts = text.split('<br/><br/>')

            angular.forEach(wodParts, function (part) {
                if (part.includes('Daily Task')) {
                    var part_lines = part.split('<br/>')

                    angular.forEach(part_lines, function (line) {
                        if (line.toLowerCase().includes('amrap')) {
                            $scope.addForm.score = line.toUpperCase();
                        }
                        if (line.toUpperCase().startsWith('E')) {
                            $scope.addForm.score = line.toUpperCase();
                        }
                    })

                    // if (part.toLowerCase().includes('amrap')) {
                    //     $scope.addForm.score = 'AMRAP'
                    // }
                    // if (part.toLowerCase().includes('emom')) {
                    //     $scope.addForm.score = 'EMOM'
                    // }
                }
            });

            if (text && !$scope.scores) {
            text = text[2] || '';

            if (text) {
                text = text.split('Daily Task')[1]
            }

            if (text) {
                text = text.split('<br/>')
            }

            if (text) {
                console.log(text)
                text = text.filter(item => item);

                if (text[0].toLowerCase().includes('emom') || text[0].toLowerCase().includes('amrap')) {
                    $scope.addForm.score = text[0].toUpperCase();
                    text.splice(0, 1)
                }


                $scope.addForm.comments = text.join('\n');
            }
        }

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

app.controller("rxWodsCtrl", ['WodService', '$scope', rxWodsCtrl]);

function rxWodsCtrl(WodService, $scope) {
    $scope.loadedPage = 1;

    var today = new Date();
    
    $scope.newWod = {
        'createdAt': today
    };
    
    $scope.nextPage = function () {
        if ($scope.loadedPage > 1) {
            $scope.loadedPage++;
        }
        $scope.getWods();
    }

    $scope.prevPage = function () {
        if ($scope.loadedPage > 1) {
            $scope.loadedPage--;
        }
        $scope.getWods();
    }

    $scope.gotoPage = function (page) {
        if (page > 1) {
            $scope.loadedPage = page;
        }
        $scope.getWods();
    }

    $scope.getWods = function() {
        WodService.getWods($scope.loadedPage)
            .then(function(response) {
                $scope.wods = response.data;
            });
    }

    $scope.getWods();

    $scope.getWod = function (id) {
        window.location.href = WodService.getWodUrl(id);
    }

    $scope.addWod = function () {
        WodService.addWod($scope.newWod)
            .then(function(response) {
                $scope.getWods();
            });        
    }

    $scope.deleteWod = function ($event, id) {
        $event.preventDefault();
        $event.stopPropagation();

        WodService.deleteWod(id)
            .then(function() {
                $scope.getWods();
            });
    }
}

})();
