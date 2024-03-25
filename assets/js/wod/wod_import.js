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
console.log(content)
        $scope.created = created;
        content = content.trim();
        // content = content.split('See you at the gym!')[1];
        content = content.replaceAll("<br/>", '\n');
        // content = content.replace("\n", '');
        // content = content.replace(/<[^>]*>?/gm, '');
        $scope.content = content; //split('See you all for a fun filled weekend workout!!')[0];
    });
}

