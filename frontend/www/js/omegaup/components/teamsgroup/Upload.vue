<template>
  <div class="card">
    <div class="card-body">
      <div class="mb-4">
        <omegaup-markdown :markdown="T.teamsGroupsCsvHelp"></omegaup-markdown>
        <div class="form-check mb-4">
          <label class="form-check-label">
            <input
              v-model="humanReadable"
              class="form-check-input"
              type="checkbox"
            />
            {{ T.passwordHumanReadable }}
          </label>
        </div>
        {{ T.groupsUploadCsvFile }}
        <input
          name="identities"
          type="file"
          accept=".csv,.txt"
          @change="readCsv"
        />
      </div>
      <template v-if="identities.length > 0">
        <h3 class="card-header">{{ T.teamsGroupEditTeams }}</h3>
        <div class="table-responsive">
          <table class="table" data-identities-as-team--table>
            <thead>
              <tr>
                <th>{{ T.teamsGroupTeamName }}</th>
                <th>{{ T.profile }}</th>
                <th>{{ T.loginPassword }}</th>
                <th>{{ T.profileCountry }}</th>
                <th>{{ T.profileState }}</th>
                <th>{{ T.profileSchool }}</th>
                <th>{{ T.teamsGroupUsernames }}</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="identity in identities"
                :key="identity.username"
                :class="{ 'alert-danger': userErrorRow === identity.username }"
              >
                <td class="username">
                  <strong>{{ identity.username }}</strong>
                </td>
                <td>{{ identity.name }}</td>
                <td class="password">
                  {{ identity.password }}
                </td>
                <td>{{ identity.country_id }}</td>
                <td>{{ identity.state_id }}</td>
                <td>{{ identity.school_name }}</td>
                <td>
                  <button
                    v-if="teamToAddUsers !== identity.username"
                    class="btn btn-primary d-inline-block mb-2"
                    @click="teamToAddUsers = identity.username"
                  >
                    {{ T.teamsGroupAddUsersToTeam }}
                    <span class="badge badge-light">
                      {{ identitiesTeams[identity.username].length }}
                    </span>
                  </button>
                  <template v-else>
                    <omegaup-common-multi-typeahead
                      :existing-options="searchResultUsers"
                      :value.sync="typeaheadUsers"
                      @update-existing-options="
                        (query) => $emit('update-search-result-users', query)
                      "
                    >
                    </omegaup-common-multi-typeahead>
                    <button
                      class="btn btn-primary d-inline-block mb-2"
                      @click="onAddUsers(identity.username)"
                    >
                      {{ T.teamsGroupAddUsersDone }}
                    </button>
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          <button
            class="btn btn-primary d-inline-block mb-2"
            name="create-identities"
            @click.prevent="
              $emit('bulk-identities', { identities, identitiesTeams })
            "
          >
            {{ T.teamsGroupCreateIdentitiesAsTeams }}
          </button>
          <div>
            <button
              class="btn btn-warning d-inline-block"
              @click.prevent="
                $emit('download-identities', identities, humanReadable)
              "
            >
              <font-awesome-icon :icon="['fas', 'download']" />
            </button>
            {{ T.groupsIdentityWarning }}
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';
import { types } from '../../api_types';
import T from '../../lang';
import omegaup_Markdown from '../Markdown.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faDownload } from '@fortawesome/free-solid-svg-icons';
import common_MultiTypeahead from '../common/MultiTypeahead.vue';

library.add(faDownload);
@Component({
  components: {
    FontAwesomeIcon,
    'omegaup-common-multi-typeahead': common_MultiTypeahead,
    'omegaup-markdown': omegaup_Markdown,
  },
})
export default class Identities extends Vue {
  @Prop() userErrorRow!: string | null;
  @Prop() searchResultUsers!: types.ListItem[];

  T = T;
  identities: types.Identity[] = [];
  identitiesTeams: { [team: string]: string[] } = {};
  humanReadable = false;
  teamToAddUsers = null;
  typeaheadUsers: types.ListItem[] = [];

  readFile(e: HTMLInputElement): File | null {
    return (e.files && e.files[0]) || null;
  }
  readCsv(ev: Event): void {
    const file = this.readFile(ev.target as HTMLInputElement);
    if (!file || file.name === '') {
      return;
    }
    const regex = /.*\.(?:csv|txt)$/;
    if (!regex.test(file.name.toLowerCase())) {
      this.$emit('invalid-file');
      return;
    }
    this.identities = [];
    this.$emit('read-csv', {
      identitiesTeams: this.identitiesTeams,
      identities: this.identities,
      file: file,
      humanReadable: this.humanReadable,
    });
  }

  onAddUsers(username: string): void {
    this.teamToAddUsers = null;
    this.identitiesTeams[username] = this.typeaheadUsers.map(
      (user) => user.key,
    );
  }
}
</script>