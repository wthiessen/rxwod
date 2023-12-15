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

