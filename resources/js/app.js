require('./bootstrap');

Vue.component('website-product-fees', require('./components/websites/WebsiteProductFees').default);

const app = new Vue({
    el: '#app'
});