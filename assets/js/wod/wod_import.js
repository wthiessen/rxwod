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
        console.log(created);

        $scope.created = created;
        $scope.content = content.split('Your Coach')[1];
    });
}

