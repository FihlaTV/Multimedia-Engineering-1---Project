angular.module('petsitting').controller('TableController', ['$scope', 'StorageService', 'uiGmapIsReady', function ($scope, StorageService) {

  $scope.users = [];

  // Loads data into scope
  StorageService.getAll().then( function (data) {
    for ( var i = 0; i < data.length; i++ ) {
      data[i].position = {
        latitude: data[i].latitude,
        longitude: data[i].longitude
      };
    }
    $scope.users = data;
  });

  $scope.map = {
    zoom: 13
  };

  // Loads Google Map centered at the users address
  $scope.getGoogleMap = function($index){
    $scope.map = {
      center: {
        latitude : $scope.users[$index].latitude,
        longitude: $scope.users[$index].longitude
      },
      zoom: 8
    };
  };

  // Toggles details-row for a table entry
  $scope.toggleDetails = function($index){
    $scope.activeRow = $scope.activeRow == $index ? -1 : $index;
  }

}]);