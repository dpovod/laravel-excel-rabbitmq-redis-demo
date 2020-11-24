<template>
  <div>
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

    <div class="mt-3">Selected file: {{ form.file ? form.file.name : '' }}</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        file: null,
      },
      noty: this.$noty,
    }
  },
  methods: {
    onSubmit(event) {
      event.preventDefault();
      let formData = new FormData();
      formData.append('file', this.form.file);
      const NOTY = this.noty;

      axios.post('/upload/submit-file',
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
      ).then(function () {
        NOTY.success("File was stored on the server!")
      }).catch(function (error) {
        NOTY.error(error.response.data.message || "Oops, something went wrong!")
      });
    },
    onReset(event) {
      event.preventDefault()
      this.form.file = null
    }
  }
}
</script>
