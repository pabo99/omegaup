<template>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div ref="markdownButtonBar" class="wmd-button-bar"></div>
          <textarea
            ref="markdownInput"
            v-model="currentMarkdown"
            class="wmd-input"
          ></textarea>
        </div>
        <div class="col-md-6">
          <omegaup-markdown
            :markdown="
              T.problemCreatorMarkdownPreviewInitialRender + currentMarkdown
            "
            preview="true"
          ></omegaup-markdown>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button class="btn btn-primary" type="submit" @click="updateMarkdown">
            {{ T.problemCreatorMarkdownSave }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Vue, Component, Ref } from 'vue-property-decorator';
import * as Markdown from '@/third_party/js/pagedown/Markdown.Editor.js';
import * as markdown from '../../../../markdown';
import T from '../../../../lang';
import * as ui from '../../../../ui';

import omegaup_Markdown from '../../../Markdown.vue';

const markdownConverter = new markdown.Converter({
  preview: true,
});

@Component({
  components: {
    'omegaup-markdown': omegaup_Markdown,
  },
})
export default class StatementTab extends Vue {
  @Ref() readonly markdownButtonBar!: HTMLDivElement;
  @Ref() readonly markdownInput!: HTMLTextAreaElement;

  T = T;
  ui = ui;
  markdownEditor: Markdown.Editor | null = null;

  currentMarkdown: string = T.problemCreatorEmpty;

  mounted(): void {
    this.markdownEditor = new Markdown.Editor(markdownConverter.converter, '', {
      panels: {
        buttonBar: this.markdownButtonBar,
        preview: null,
        input: this.markdownInput,
      },
    });
    this.markdownEditor.run();
  }

  updateMarkdown() {
    this.$store.commit('updateMarkdown', this.currentMarkdown);
    this.$emit('show-update-success-message');
  }
}
</script>

<style lang="scss" scoped>
@import '../../../../../../sass/main.scss';
@import '../../../../../../third_party/js/pagedown/demo/browser/demo.css';

.wmd-preview,
.wmd-button-bar {
  background-color: var(--wmd-button-bar-background-color);
}
</style>
