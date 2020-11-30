<template>
  <div class="bg-white rounded-lg shadow mt-3 p-5">
    <b-form @submit="onSubmit" @reset="onReset">
      <b-form-group id="input-group" label="Choose a file" label-for="input-file">
        <b-form-file
            id="input-file"
            v-model="form.file"
            :state="Boolean(form.file)"
            placeholder="Choose a file or drop it here..."
            drop-placeholder="Drop file here..."
            required
        ></b-form-file>
      </b-form-group>

      <b-button type="submit" variant="primary">Submit</b-button>
      <b-button type="reset" variant="danger">Reset</b-button>
    </b-form>

    <div class="mt-3 mb-3">Selected file: {{ form.file ? form.file.name : '' }}</div>

    <progress-bar v-for="job in jobs" :key="job.uuid"
                  :value="job.progress" :variant="!job.failed ? 'success' : 'danger'" v-bind:style="{marginBottom: 10}" :title="job.uuid"></progress-bar>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        file: null,
      },
      jobs: [],
    }
  },
  methods: {
    onSubmit(event) {
      event.preventDefault();
      let formData = new FormData();
      formData.append('file', this.form.file);
      const NOTY = this.$noty;

      axios.post('/api/upload/submit-file',
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
      ).then(function () {
        NOTY.success("Importing of the uploaded file in progress")
      }).catch(function (error) {
        NOTY.error(error.response.data.message || "Oops, something went wrong!")
      });
    },
    onReset(event) {
      event.preventDefault()
      this.form.file = null
    },
    changeProgress(jobUuid, progress, failed = false) {
      const index = this.jobs.findIndex(function (job) {
        return job.uuid === jobUuid;
      });

      if (index !== -1) {
        this.jobs[index].progress = progress;
        this.jobs[index].failed = failed;
        return;
      }

      this.jobs.push({uuid: jobUuid, progress: progress, failed: failed});
    },
    markProgressFailed(jobUuid) {
      this.changeProgress(jobUuid, 100, true);
    }
  },
  mounted() {
    const CHANNEL = 'import-progress';

    window.Echo.channel(CHANNEL)
        .listen('ImportProgressChanged', data => {
          this.changeProgress(data.importJobUuid, data.progress);
        })
        .listen('ImportFailed', data => {
          this.markProgressFailed(data.importJobUuid);

        })
  },
}
</script>
