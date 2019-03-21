<template>
  <div class="card card-body col-md-12 container" style="margin-top:25px;">
    <div class=" row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Registrar producto</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <span class="ml-1 v-else">Enviar <i class="icon-paper-plane"></i></span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-6">
                <label>Clave</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.clabe}" v-model="product.clabe" placeholder="9999">
                <div class="invalid-feedback" v-if="errors.clabe">{{errors.clabe[0]}}</div>
            </div>
            <div class="form-group col-md-3">
              <label>Categoria</label>
              <input type="number" class="d-none form-control" :class="{'is-invalid': errors.category}" v-model="product.category" placeholder="Categoria..">
              <center>
                <rate :length="5" v-model="product.category" :value="product.category" :showcount="false" />
              </center>
            </div>
            <div class="form-group col-md-6">
                <label>Nombre</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.name}" v-model="product.name" placeholder="Hotel...">
                <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Tipo de producto</label>
                <multiselect
                  v-model="product.product_types"
                  :options="product_types"
                  openDirection="bottom"
                  track-by="id"
                  label="name"
                  :class="{'border border-danger rounded': errors.product_types}">
                </multiselect>
                <small class="form-text text-danger" v-if="errors.product_types">{{errors.product_types[0]}}</small>
            </div>
            <div class="form-group col-md-6">
                <label>Cargar imagen</label>
                <div class="row">
                    <div class="col-md-6" v-if="image">
                        <img :src="image" class="img-responsive" height="90" width="190">
                     </div>
                    <div class="col-md-6">
                        <input type="file" v-on:change="onImageChange" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label>Descripcion del hotel</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.description}" v-model="product.description" placeholder="....">
                <div class="invalid-feedback" v-if="errors.description">{{errors.description[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Ubicación</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.location}" v-model="product.location" placeholder="9202163">
                <div class="invalid-feedback" v-if="errors.location">{{errors.location[0]}}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
export default {
  data () {
    return {
      product: {},
      product_types: [],
      errors: {},
      image: '',
      url: null,
      submiting: false,
    }
  },
  mounted () {
    this.getProductTypes()
    this.csrf = window.Laravel.csrfToken;
  },
  methods: {
      create () {
        if (!this.submiting) {
          this.submiting = true
          axios.post(`../api/products/store`, this.product)
          .then(response => {
              this.$toasted.global.error('Created products!')
              location.href = '../products'
          })
          .catch(error => {
            this.errors = error.response.data.errors
            this.submiting = false
          })

        }
      },
      getProductTypes() {

          axios.get(`../api/products_type/all`).then(response => {
              this.product_types = response.data
          })
          .catch(error => {
              this.errors = error.response.data.errors
          })
      },
      onFileChange(e) {
        const file = e.target.files[0];
        this.url = URL.createObjectURL(file);
        this.product.url_image = this.url;
      }
      ,onImageChange(e) {
                  let files = e.target.files || e.dataTransfer.files;
                  if (!files.length)
                      return;
                  this.createImage(files[0]);
      },
      createImage(file) {
          let reader = new FileReader();
          let vm = this;
          reader.onload = (e) => {
              vm.image = e.target.result;
              this.product.url_image = e.target.result;
          };
          reader.readAsDataURL(file);
      },
  }
}
</script>
