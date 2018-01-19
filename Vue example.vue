<!-- This is a table component, it shows a list of employees with their details. -->
<template>
  <div>
    <v-btn icon class="red--text">
      <v-icon>delete_forever</v-icon>
    </v-btn>
    <v-btn primary :to="{ name: 'resource.new' }">
      Add new
    </v-btn>
    <v-data-table
            v-model="selected"
            v-bind:headers="headers"
            v-bind:items="employees"
            select-all

            v-bind:pagination.sync="pagination"
            selected-key="name"
            class="elevation-1"
    >

      <template slot="items" scope="props">
        <tr :active="props.selected" @click="viewEmployee(props.item.id)">
          <td @click="noViewEmployee">
            <v-checkbox
                    @click="props.selected = !props.selected"
                    primary
                    hide-details
                    :input-value="props.selected"
            ></v-checkbox>
          </td>
          <td> {{ props.item.name }} </td>
          <td> {{ props.item.city }} </td>
          <td> {{ props.item.position }} </td>
          <td> {{ props.item.date  | truncate(11, ' ')  }} </td>
          <td> {{ props.item.project }} </td>
          <td> {{ props.item.phone }} </td>
          <td @click="noViewEmployee">
            <div class="icons">
              <v-btn icon class="green--text"  :to="{  name: 'employees.edit', params: { id: props.item.id } }">
                <v-icon>edit</v-icon>
              </v-btn>
            </div>
          </td>
        </tr>
      </template>
    </v-data-table>
  </div>
</template>

<script>

	import resource from '@/services/resource';
	import Vue from 'vue';

	let canOpen = true;

	export default {
		data() {
			return {
				selected: [],
				resourceName: 'employees',
				headers: [
					{ text: 'Name', align: 'center', value: 'name' },
					{ text: 'Place', align: 'center', value: 'city' },
					{ text: 'Function', align: 'center', value: 'position' },
					{ text: 'Contract', align: 'center', value: 'date' },
					{ text: 'Project', align: 'center', value: 'project' },
					{ text: 'Phone number', align: 'center', value: 'phone' },
				],
				pagination: {
					sortBy: 'name',
				},
			};
		},

		computed: {
			employees() {
				return this.$store.state.resource.collection;
			},
		},

		components: {
			VLayout: require('@/layouts/vuetify.vue'),
		},

		created() {
			resource.get(this.resourceName);
		},

		methods: {
			destroy(resourceId) {
				resource.destroy(this.resourceName, resourceId);
			},
			toggleAll() {
				if (this.selected.length) this.selected = [];
				else this.selected = this.items.slice();
			},
			viewEmployee(resourceId) {
				if (canOpen) {
					Vue.router.push({
                        name: 'employees.details',
                        params: {
                            id: resourceId,
                        },
                    });
				} else { canOpen = true; }
			},
			noViewEmployee() {
				canOpen = false;
			},
		},
	};
</script>

<style lang="scss">
  .icons {
    display: inline-flex;
  }
</style>