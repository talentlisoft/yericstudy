import angular from 'angular';
import TraineeInterface from '../interfaces'
import toastr from 'angular-toastr';
import ngoutsideclick from '@iamadamjowett/angular-click-outside';

export default angular.module('mytrainModule', ['TraineeInterfaces', 'toastr', 'angular-click-outside']);