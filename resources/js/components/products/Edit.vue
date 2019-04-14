<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Editar Producto</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
                <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                <i class="fas fa-check" v-else></i>
                <span class="ml-1">Guardar</span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
              <div class="form-group col-md-6">
                <label>Categoria</label>
                <multiselect
                  v-model="product.type"
                  :options="type"
                  openDirection="bottom"
                  :class="{'border border-danger rounded': errors.product_types}">
                </multiselect>
                <input style="display: none;" type="number" class="form-control" :class="{'is-invalid': errors.category}" v-model="product.category" placeholder="Categoria..">
                <star-rating v-if="product.type == ['Estrellas']" v-model="product.category" :value="product.category" :item-size="30"></star-rating>
                <image-rating v-if="product.type == ['Diamantes']" v-model="product.category" :value="product.category" :item-size="40" src="../../public/img/diamante.png"></image-rating>
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
                      <div class="col-md-6" v-else="product.url_image">
                        <img :src="'../../storage/app/public/product_img/' + product.url_image" class="img-responsive"  height="90" width="190">
                        </div>
                      <div class="col-md-6">
                          <input type="file" v-on:change="onImageChange" class="form-control">
                      </div>
                  </div>
              </div>
              <div class="form-group col-md-6">
                  <label>Descripcón del hotel</label>
                  <input type="text" class="form-control" :class="{'is-invalid': errors.description}" v-model="product.description" placeholder="....">
                  <div class="invalid-feedback" v-if="errors.description">{{errors.description[0]}}</div>
              </div>
              <div class="form-group col-md-6">
                  <label>Ubicación</label>
                  <input type="text" class="form-control" :class="{'is-invalid': errors.location}" v-model="product.location" placeholder="9202163">
                  <div class="invalid-feedback" v-if="errors.location">{{errors.location[0]}}</div>
              </div>
              <div class="form-group col-md-12">
                <label>Comentarios extra</label>
                <textarea class="form-control" rows="5" :class="{'is-invalid': errors.extra_comments}"  v-model="product.extra_comments"></textarea>
                <div class="invalid-feedback" v-if="errors.extra_comments">{{errors.extra_comments[0]}}</div>
            </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
        product: {
          url_image: 'default.jpeg',
          base64: null
        },
        product_types: [],
        errors: {},
        image: '',
        url: null,
        submiting: false,
    }
  },
  mounted () {
    this.getProduct()
    this.getProductType()
  },
  methods: {
    getProduct() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`../../api/products/get-product/${res[3]}`)
      .then(response => {
          this.product = response.data
          this.product.base64 = null
      })
      .catch(error => {
          this.$toasted.global.error('User does not exist!')
          location.href = '../../products'
      })
      .then(() => {
        this.loading = false
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`../../api/products/update/${this.product.id}`, this.product)
        .then(response => {
            this.$toasted.global.error('Updated product!')
            location.href = '../../products'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
    generateBase64 () {
      let canvas = document.createElement('CANVAS')
      let img = document.createElement('img')
      img.src = '../../storage/app/public/product_img/' + this.product.url_image
      img.onload = () => {
        canvas.height = img.height
        canvas.width = img.width
        this.product.url_image = canvas.toDataURL('image/png');
        canvas = null
      }
      
      img.onerror = error => {
        this.error = 'Could not load image, please check that the file is accessible and an image!'
      }
    },
    getProductType() {
        axios.get(`../../api/products_type/all`).then(response => {
            this.product_types = response.data
        })
        .catch(error => {
            this.errors = error.response.data.errors
        })
    },
    onChange() {
        this.$delete(this.product, 'subgroup_id')
        this.$delete(this.product, 'subgroup')

        axios.post(`../../api/groups/subgroup_by_gruoup_id`, {
            id: this.product.group.id
        })
        .then(response => {
            this.subgroups = response.data;
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
            this.product.url_image = this.product.base64 = e.target.result;
        };
        reader.readAsDataURL(file);
    },
  }
}
</script>
