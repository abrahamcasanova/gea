/*
* Simple plugin adding $can method to Vue.
* Require permissions loaded in window.Laravel.permissions and current userId in window.Laravel.userId
*/

const Acl = {
    install(Vue, options) {
        // If authorID id is equal to current userId permission is always granted
        Vue.prototype.$can = function (permission, authorId = false) {
            if (window.Laravel.userId == authorId) {
                return true;
            }
            if (window.Laravel.permissions.indexOf(permission) !== -1) {
                return true;
            }else{
                return false;
            }
        }
    }
};

module.exports = Acl;