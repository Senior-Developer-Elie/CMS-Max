<template>
    <div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="sync_from_client" v-model="syncFromClient" />
                <strong class="ml-1">Sync From Client</strong>
            </label>
        </div>

        <div class="form-group" v-for="(websiteProduct, index) in websiteProducts" :key="`crm-product-key-${index}`">
            <label class="mb-1">
                {{ products[websiteProduct.crmProductKey] }}
            </label>
            <div class="d-flex">
                <select class="form-control mr-2" v-model="websiteProduct.availability" @change="onChangeAvailability($event, websiteProduct)">
                    <option value="available">Available</option>
                    <option value="not-available">Not Available</option>
                </select>

                <select 
                    class="form-control mr-2"
                    :name="`website_products[${websiteProduct.crmProductKey}][frequency]`"
                    v-model="websiteProduct.frequency"
                    :style="`${getStyleForManualValue(websiteProduct)}`">
                    <option value="1">Monthly</option>
                    <option value="23">Yearly</option>
                </select>

                <input 
                    class="form-control" 
                    :name="`website_products[${websiteProduct.crmProductKey}][value]`" 
                    type="text" 
                    placeholder="0"
                    v-model="websiteProduct.value"
                    :style="`${getStyleForManualValue(websiteProduct)}`">
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "WebsiteProductFees",

    props: [
        'initialSyncFromClient',
        'products',
        'initialWebsiteProducts'
    ],
    
    data() {
      return {
          syncFromClient: false,
          websiteProducts: [],
      }
    },

    methods: {

        getProductAvailability(product) {

            if (product.value == -1) {
                return "not-available"
            }

            return "available"
        },

        getStyleForManualValue(websiteProduct) {
            if (! this.syncFromClient && websiteProduct.availability == 'available') {
                return 'visibility: visible;'
            }

            return 'visibility: hidden;'
        },

        onChangeAvailability($event, websiteProduct) {
            if ($event.target.value == 'not-available') {
                websiteProduct.value = -1;
            } else {
                websiteProduct.value = 0;
            }
        }
    },

    created () {
        this.syncFromClient = this.initialSyncFromClient;

        for (var crmProductKey in this.initialWebsiteProducts) {
            let product = this.initialWebsiteProducts[crmProductKey];
            
            this.websiteProducts.push({
                crmProductKey,
                availability: this.getProductAvailability(product),
                ...product
            })
        }
    },
  }
</script>

<style lang="css">
</style>
