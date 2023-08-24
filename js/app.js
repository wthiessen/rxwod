/** rxwod 2023 **/
'use strict';
// console.log('wodjs');
(function(angular) {

    var app = angular.module("myApp", [])
    .filter('trustAsHtml', ['$sce', function($sce){
        console.log('ALLyourBASE')

        return function(val) {
            return $sce.trustAsHtml(val);
        };
    }])
    .config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{:').endSymbol(':}');
    });

})(angular);

'use strict';

(function(angular) {
var app = angular.module('myApp');

app.controller("RxWod-ShowCtrl", ['$scope, $http', rxWodShowCtrl]);

function rxWodShowCtrl($scope, $http) {
    $scope.getWod = function () {
        $http({
            url: '../api/wods/{{ wod.id }}',
            method: 'GET',
        }).then(function (response) {
            $scope.wod = response.data;

            // var text = $scope.wod.wod;

            // text = text.split('<br/><br/>')

            // if (text && !$scope.scores) {
            //     text = text[2] || '';
            //
            //     if (text) {
            //         text = text.split('Daily Task')[1]
            //     }
            //
            //     if (text) {
            //         text = text.split('<br/>')
            //     }
            //
            //     if (text) {
            //         text = text.filter(item => item);
            //
            //         if (text[0].toLowerCase().includes('emom') || text[0].toLowerCase().includes('amrap')) {
            //             $scope.addForm.score = text[0].toUpperCase();
            //             text.splice(0, 1)
            //         }
            //         $scope.addForm.comments = text.join('\n');
            //     }
            //
            // }

// console.log(text);
//             $scope.getLeaderboard();
//             $scope.getLiftRecordsFor();
        });

        $scope.getWod();
    }

    console.log('rxWodShowCtrl')
}
})(angular);


'use strict';

(function(angular) {
app.controller("rxWodsCtrl", function($scope, $http) {

    console.log('wodjs');

    $scope.loadedPage = 1;

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

    $scope.getWods = function () {
        $http({
            url: 'api/wods.json?page=' + $scope.loadedPage,
            method: 'GET',
        }).then(function(response) {
            $scope.wods = response.data;
        });
    }

    $scope.getWods();

    $scope.getWod = function (id) {
        console.log('getwod')
        window.location.href = 'wod/' + id;
    }

    $scope.deleteWod = function (id) {
        $http({
            url: 'api/wods/' + id,
            method: 'DELETE',
        }).then(function(response) {
            $scope.getWods();
        });
    }
});
})(angular);
