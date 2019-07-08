import angular from 'angular';
import uirouter from '@uirouter/angularjs';
import loading from '../shared/loading';
import nav from '../shared/nav';
import nganimiate from 'angular-animate';
import mytrainModule from './mytrain/mytrian';

export default angular.module('studyapp', ['mainMenu', 'ngAnimate', 'loadinIndicator', 'ui.router', 'mytrainModule']);