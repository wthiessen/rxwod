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
