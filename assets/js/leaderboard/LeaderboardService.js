app.service('LeaderboardService', ['$http', '$location', LeaderboardService]);

function LeaderboardService($http, $location)
{
    this.baseUrl = '/api2/leaderboards';

    this.getLeadboardScores = function (wodId) {
        return $http({
            url: '/api2/leaderboards/' + wodId,
            method: 'GET',
        });
    }

    this.addScore = function (data) {
        return $http({
            url: '/api2/leaderboards',
            data: data,
            method: 'POST',
        })
    }

    this.editScore = function (data) {
        return $http({
            url: '/api2/leaderboards/' + data.id,
            data: data,
            method: 'PUT',
        });
    }
}
