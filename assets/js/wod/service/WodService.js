app.service('WodService', ['$http', WodService]);

function WodService($http)
{
    this.baseUrl = 'api/wods';

    this.getWods = function (page) {
        return $http({
            url: '/api2/wods',
            // url: this.baseUrl + '.json?page=' + page,
            method: 'GET',
        });
    };

    this.getWodUrl = function (id) {
        return 'wod/' + id;
    };

    this.getWod = function (id) {
        return $http({
            url: '/api2/wod/' + id,
            method: 'GET',
        });
    };

    this.deleteWod = function (id) {
        return $http({
            url: '../../' + this.baseUrl + '/' + id,
            method: 'DELETE',
        });
    }

    this.editWod = function (id, data) {
        return $http({
            url: '/api2/wod/' + id,
            data: data,
            method: 'PUT',
        });
    }

    this.addWod = function (data) {
        return $http({
            url: '/api2/wods',
            data: data,
            method: 'POST',
        });
    }

    this.getWodTypes = function () {
        return $http({
            url: '../../wod/types',
            method: 'GET',
        });
    }
}
