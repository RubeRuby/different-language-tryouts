<!--
  This is a widget component. It connects to a websocket and retrieves processed events.
  Then it displays what type of event it is, what the action is,
  what the name of the affected resource is and when the event took place.
-->
<template>
  <div class="wu-box">
    <div class="wu-box__margin">
      <h3 class="wu-box__title">What's up</h3>
      <p v-for="event in events">
        <span class="wu-box__date">{{ event.date }}</span>
        <br>
        The {{ event.type }} {{ event.name }} was {{ event.action }}
      </p>
    </div>
  </div>
</template>

<script>
	export default {
		name: 'odWhatsUp',

		data() {
			return {
				events: [],
			};
		},

		created() {
			if (window.Echo) {
				window.Echo.channel('office-dashboard')
				.listen('GenericModelBroadcast', (e) => {
					this.addEvent(e, this.events);
				});
			}
		},

		beforeDestroy() {
			window.Echo.leave('office-dashboard');
		},

		methods: {
			addEvent(e, events) {
				if (events.length >= 5) {
					events.splice(0, 1);
				}
				events.push({ date: e.date, name: e.name, action: e.action, type: e.type });
			},
		},
	};
</script>

<style lang="scss">
  .wu-box {
    background-color: $white;

    &__title {
      font-size: 16px;
      font-weight: 600;
    }

    &__date {
      color: $light-blue;
    }

    &__margin {
      margin-left: 10px;
    }
  }
</style>
