var app = new Vue({
  el: '#app',
  data: {
    message: 'Hello world',
    progress: 0.27
  },
  computed: {
    progressPercent: function(){
      return Math.round(this.progress * 100) + ' %';
    }
  },
  methods: {
    next: function(){
      this.message = 'next';
    },
    previous: function(){
      this.message = 'previous';
    }
  }
})