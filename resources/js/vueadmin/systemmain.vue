<template>
    <div>
        <div class="sidebar-nav d-print-none" id="sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/system/topicssummary" active-class="active"><i class="fa fa-university fa-fw"></i>题目管理</router-link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"><i class="fa fa-flag-checkered fa-fw"></i>训练管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"><i class="fa fa-question-circle-o fa-fw"></i>人工审核</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"><i class="fa fa-users fa-fw"></i>用户管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"><i class="fa fa-graduation-cap fa-fw"></i>学员管理</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="maincontent" id="maincontent">
            <router-view class="container-fluid"></router-view>
        </div>
    </div>

</template>

<script>
export default {
    
}
</script>