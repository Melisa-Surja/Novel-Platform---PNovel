<template>
  <div>
    <ul class="grid grid-cols-2">
      <li v-for="p in permissions" :key="p" class="py-1">
        <input :id="p" type="checkbox" name="permission" class="form-checkbox h-4 w-4 text-primary" :checked="chosenPermissions.includes(p)" :value="p" @change="updatePermission(p)">
        <label :for="p" class="ml-1 font-medium text-gray-700">{{p}}</label> 
      </li>
    </ul>
  </div>
</template>


<script>
  export default {
    props: {
      checked: Boolean,
      initRole: {
        type: String,
        default: "Reader"
      },
      url: Object,
      permissions: Array
    },
    data() {
      return {
        chosenRole: "",
        chosenPermissions: []
      }
    },
    methods: {
      changeRole(newRole) {
        if (this.chosenRole == newRole) return;
        
        this.chosenRole = newRole;

        if (newRole == "Super Admin") {
          this.chosenPermissions = this.permissions;
          return;
        }

        // load permissions
        axios.get(
          this.url.getPermissions, {params: {role: this.chosenRole}}
        )
        .then(response => {
          this.chosenPermissions = response.data;
        })
        .catch(error => {
        });
      },
      updatePermission(p) {
        if (this.chosenRole == "Super Admin") return;

        var newVal = !this.chosenPermissions.includes(p);
        axios.patch(
          this.url.updatePermission, {
            role: this.chosenRole,
            permission: p, 
            value: newVal
            }
        )
        .then(response => {
          // console.log(response.data);
          // success? then newval
          if (newVal)
            this.chosenPermissions.push(p)
          else
            this.chosenPermissions.splice(this.chosenPermissions.indexOf(p), 1)
        })
        .catch(error => {
          console.log(error);
        });
      }
    },
    mounted() {
      this.changeRole(this.initRole);
      this.$eventBus.$on("change_role", newRole => {
        this.changeRole(newRole);
      });
    },
  }
</script>