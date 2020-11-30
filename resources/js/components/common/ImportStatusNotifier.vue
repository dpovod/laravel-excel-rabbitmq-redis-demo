<template>

</template>

<script>
export default {
  mounted() {
    const CHANNEL = 'import-progress';
    const NOTY = this.$noty;

    window.Echo.channel(CHANNEL)
        .listen('ImportCompleted', data => {
          NOTY.success(`Import '${data.importJobUuid}' completed`);
        })
        .listen('ImportFailed', data => {
          NOTY.error(`Import '${data.importJobUuid}' failed: ${data.reason}`);
        });
  }
}
</script>
