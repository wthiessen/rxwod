app.controller("rxWodsCtrl", ['WodService', '$scope', rxWodsCtrl]);

function rxWodsCtrl(WodService, $scope) {
    $scope.loadedPage = 1;

    var today = new Date();//.toISOString().slice(0, 10)
    
    $scope.newWod = {
        'createdAt': today
    };
    
    // console.log($scope.newWod)

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
                console.log(response)
                $scope.getWods();
            });        
    }

    // $scope.deleteWod = function (id) {
    //     WodService.deleteWod(id)
    //         .then(function() {
    //             $scope.getWods();
    //         });
    // }
}
