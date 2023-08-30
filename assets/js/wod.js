app.controller("rxWodsCtrl", ['WodService', '$scope', rxWodsCtrl]);

function rxWodsCtrl(WodService, $scope) {

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
        $http({
            url: 'api/wods',
            data: data,
            method: 'POST',
        });

        $.ajax({
            url: newWodUrl,
            method: 'POST',
            dataType: 'json',
            data: JSON.stringify(json),
            contentType: 'application/json',
            success: function(result){
                $('.add-wod').addClass("hidden");
                $('.js-wod-log-table.hidden:first').removeClass("hidden");
                $('.add-wod-form').removeClass("hidden");
                $('.add-wod-form-2').addClass("hidden");
            },
            error: function(request,status,errorThrown) {
            }
        });
    }

    // $scope.deleteWod = function (id) {
    //     WodService.deleteWod(id)
    //         .then(function() {
    //             $scope.getWods();
    //         });
    // }
}
