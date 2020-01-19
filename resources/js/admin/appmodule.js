import angular from 'angular';
import system from './system/system';
import loading from '../shared/loading';
import nav from '../shared/nav';
import admininterface from './interfaces';
import nganimiate from 'angular-animate';
import changepassword from '../shared/changepassword';
import yericfilter from '../shared/customfilter';

export default angular.module('studyapp', ['systemmodule', 'AdminchangePassword', 'mainMenu', 'Admininterface', 'ngAnimate', 'interfaceInteractor', 'yericfilter']);