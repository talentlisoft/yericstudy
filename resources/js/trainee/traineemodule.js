import angular from 'angular';
import uirouter from '@uirouter/angularjs';
import loading from '../shared/loading';
import nav from '../shared/nav';
import nganimiate from 'angular-animate';
import mytrainModule from './mytrain/mytrian';
import uiboostrap from 'ui-bootstrap4';
import yericfilter from '../shared/customfilter'

export default angular.module('studyapp', ['mainMenu', 'ngAnimate', 'interfaceInteractor', 'ui.router', 'mytrainModule', 'ui.bootstrap', 'yericfilter']);