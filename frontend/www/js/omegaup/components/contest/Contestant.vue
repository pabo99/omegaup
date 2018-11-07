<template>
  <div>
    <div class="panel panel-primary contestants-input-area">
      <div class="panel-body">
        <form class="form"
              v-on:submit.prevent="onSubmit">
          <div class="form-group">
            <label>{{T.wordsUser}}</label> <omegaup-autocomplete v-bind:init=
            "el =&gt; UI.userTypeahead(el)"
                 v-model="contestant"></omegaup-autocomplete>
          </div><button class="btn btn-primary user-add-single"
                type="submit">{{T.contestAdduserAddUser}}</button>
          <hr>
          <div class="form-group">
            <label>{{T.wordsMultipleUser}}</label>
            <textarea class="form-control contestants"
                 rows="4"
                 v-model="contestants"></textarea>
          </div><button class="btn btn-primary user-add-bulk"
                type="submit">{{T.contestAdduserAddUsers}}</button>
        </form>
      </div>
      <table class="table table-striped participants">
        <thead>
          <tr>
            <th>{{T.wordsUser}}</th>
            <th>{{T.contestAdduserRegisteredUserTime}}</th>
            <th v-if="contest.window_length != null">{{T.wordsEndTimeContest}}</th>
            <th>{{T.contestAdduserRegisteredUserDelete}}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users">
            <td><omegaup-user-username v-bind:linkify="true"
                                   v-bind:username="user.username"></omegaup-user-username></td>
            <td>{{user.access_time}}</td>
            <td v-if="contest.window_length != null">
              <omegaup-datetimepicker v-bind:finish="contest.finish_time"
                  v-bind:start="contest.start_time"
                  v-if="user.end_time"
                  v-model="user.end_time"></omegaup-datetimepicker> <a class=
                  "glyphicon glyphicon-floppy-disk"
                  href="#contestants"
                  v-if="user.end_time"
                  v-on:click="onSaveEndTime(user)"></a>
            </td>
            <td><button class="close"
                    type="button"
                    v-bind:title="T.contestAdduserRegisteredUserDelete"
                    v-on:click="onRemove(user)">×</button></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="panel panel-primary">
      <div class="panel-body">
        {{T.pendingRegistrations}}
      </div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>{{T.wordsUser}}</th>
            <th>{{T.userEditCountry}}</th>
            <th>{{T.requestDate}}</th>
            <th>{{T.currentStatus}}</th>
            <th>{{T.lastUpdate}}</th>
            <th>{{T.contestAdduserAddContestant}}</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</template>

<script>
import {T, UI} from '../../omegaup.js';
import Autocomplete from '../Autocomplete.vue';
import DateTimePicker from '../DateTimePicker.vue';
import user_Username from '../user/Username.vue';

export default {
  props: {
    data: Array,
    contest: Object,
  },
  data: function() {
    return {
      T: T,
      UI: UI,
      contestant: '',
      contestants: '',
      users: this.data,
      selected: {},
    };
  },
  methods: {
    onSaveEndTime: function(user) {
      this.selected = user;
      this.$parent.$emit('save-end-time', this);
    },
    onSubmit: function() { this.$parent.$emit('add-user', this);},
    onRemove: function(user) {
      this.selected = user;
      this.$parent.$emit('remove-user', this);
    },
  },
  components: {
    'omegaup-autocomplete': Autocomplete,
    'omegaup-datetimepicker': DateTimePicker,
    'omegaup-user-username': user_Username,
  },
};
</script>

<style>

  table.participants > tbody > tr > td > input {
    width: initial;
    display: initial;
  }

  table.participants > tbody > tr > td > a {
    text-decoration: none;
  }

</style>
