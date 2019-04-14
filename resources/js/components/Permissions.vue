<template>
</template>
<script>
  export default {
    methods: {
      $can(permissionName) {
      	console.log(permissionName)
        return Permissions.indexOf(permissionName) !== -1;
      },
    },
  };
</script>