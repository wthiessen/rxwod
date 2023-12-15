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
