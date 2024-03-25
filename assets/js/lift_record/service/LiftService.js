app.service('LiftRecordService', ['$http', '$location', LiftRecordService]);

function LiftRecordService($http, $location)
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

    this.getLiftRecordsByWodFor = function (id) {
        return $http({
            url: '/api2/lift_records/' + id,
            method: 'GET',
        });
    }

    this.addLiftRecord = function (data) {
        return $http({
            url: '/api2/lift_records',
            data: data,
            method: 'POST',
        });
    };

    this.editLiftRecord = function (data) {
        return $http({
            url: '/api2/lift_records/' + data.id,
            data: data,
            method: 'PUT',
        });
    };

    // this.getLiftDataFromWod = function (id) {
    //     return $http({
    //         url: '/lift_records/import/' + id,
    //         method: 'GET',
    //     });
    // };

    // this.parseWodSkill = function (wod) {
    //     var temp = {};
    //     var collect = false;
    //     var skill = [];
    //     var skillArray = [];
    //     skillArray = skill;
    //     skillArray.comment = [];
            
    //     skillArray.exercise = wod.Lift[1];
    //     skillArray.repScheme = wod.Lift[2];
    //     skillArray.comment = skillArray.repScheme = wod.Lift[3] + wod.Lift[4] + wod.Lift[5];
            
    //     return skillArray;
    // };
}
