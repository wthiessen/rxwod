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
            url: this.baseUrl + '/' + id,
            method: 'DELETE',
        });
    }
    //
    // this.addTag = function (tag) {
    //     return DataSvc.post(baseUrl, tag);
    // };
    //
    // this.updateTag = function (tag) {
    //     var url = baseUrl.concat('/', tag.id);
    //     return DataSvc.patch(url, tag);
    // };
    //
    // this.removeTag = function (tag) {
    //     var url = baseUrl.concat('/', tag.id);
    //     return DataSvc.delete(url, tag);
    // };
    //
    // this.checkInUse = function(tag) {
    //     var url = baseUrl.concat('/', tag.id, '/check_in_use');
    //     return DataSvc.get(url);
    // };
    //
    // this.getResourceName = function() {
    //     return 'File Tag';
    // };
}
