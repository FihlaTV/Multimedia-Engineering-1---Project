angular.module( 'petsitting' ).config( [ '$routeProvider', '$locationProvider', function ( $routeProvider, $locationProvider ) {

  $routeProvider
    .when( '/', {
      templateUrl: 'layout/front-page.html'
    } )
    .when( '/submit', {
      templateUrl: 'layout/submit.html'
    } )
    .when( '/table', {
      templateUrl: 'layout/table.html'
    } )
    .otherwise( '/' );

  $locationProvider.html5Mode( true );

} ] );