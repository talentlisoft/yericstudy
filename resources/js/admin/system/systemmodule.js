import angular from 'angular';
import uirouter from '@uirouter/angularjs';
import uibootstrap from 'ui-bootstrap4';
import toastr from 'angular-toastr';
import ngoutsideclick from '@iamadamjowett/angular-click-outside';

export default angular.module('systemmodule', ['ui.router', 'ui.bootstrap', 'toastr', 'angular-click-outside']);