app.controller("rxWodImportCtrl", ['$scope', '$http', '$attrs', wodImportCtrl]);

function wodImportCtrl($scope, $http, $attrs) {
    $scope.content = '';
console.log($attrs.glofoxUrl)
    $http({
        url: $attrs.glofoxUrl,
        method: 'GET',
        headers: {
            Authorization: 'Bearer ' + $attrs.token,
        }
    }).then(function (response) {
        var content = response.data.data[0].content;
        $scope.content = content.split('Matt')[1];
    });
}

