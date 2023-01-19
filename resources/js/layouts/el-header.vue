<template>
    <el-header class="dashboard__header">
        <div class="h4 mb-0" id="title">Quarry Management System</div>

        <el-dropdown :hide-on-click="false" trigger="click">
            <span class="el-dropdown-link">
                <span class="fw-bold me-2">
                    {{ this.$root.user.name }} 
                    <i class="text-danger fs-italic small">({{ this.$root.user.type }})</i>
                </span>
                <img src="/img/default-user.png" id="user-avatar">
            </span>
            <template #dropdown>
                <el-dropdown-menu>
                    <el-dropdown-item @click="gotoProfilePage()">Profile</el-dropdown-item>
                    <el-dropdown-item>Change Password</el-dropdown-item>
                    <el-dropdown-item  @click="this.$root.logout()">Logout</el-dropdown-item>
                </el-dropdown-menu>
            </template>
        </el-dropdown>
    </el-header>
</template>

<script>
    export default {
        data () {
            return {

            }
        }
    }
</script>