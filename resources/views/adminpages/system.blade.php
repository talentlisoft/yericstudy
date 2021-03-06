@component('components.adminsubmain')
<li class="nav-item">
    <a class="nav-link" ui-sref="system.topics.summary" ui-sref-active="{'active':'system.topics.**'}"><i class="fa fa-university fa-fw"></i>题目管理</a>
</li>
<li class="nav-item">
    <a class="nav-link" ui-sref="system.trainings.list" ui-sref-active="{'active':'system.trainings.**'}"><i class="fa fa-flag-checkered fa-fw"></i>训练管理</a>
</li>
<li class="nav-item">
    <a class="nav-link" ui-sref="system.manualaudit" ui-sref-active="{'active':'system.manualaudit'}"><i class="fa fa-question-circle-o fa-fw"></i>人工审核</a>
</li>
<li class="nav-item">
    <a class="nav-link" ui-sref="system.users.list" ui-sref-active="{'active':'system.users.**'}"><i class="fa fa-users fa-fw"></i>用户管理</a>
</li>
<li class="nav-item">
    <a class="nav-link" ui-sref="system.trainees.list" ui-sref-active="{'active':'system.trainees.**'}"><i class="fa fa-graduation-cap fa-fw"></i>学员管理</a>
</li>
@endcomponent
