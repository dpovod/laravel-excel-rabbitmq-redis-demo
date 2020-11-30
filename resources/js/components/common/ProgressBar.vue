<template>
  <div class="container">
    <h5>{{ title }}</h5>
    <b-progress :max="max" show-progress animated :value="value" :animated="value !== max"
                :variant="variant"></b-progress>
  </div>
</template>

<script>
export default {
  props: [
    'value',
    'title',
    'variant',
  ],
  data() {
    return {
      max: 100,
    }
  },
}
</script>
