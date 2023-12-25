export default {
    methods: {
        $can(permissionName) {
            return Permission.indexOf(permissionName) !== -1;
        },
    },
};