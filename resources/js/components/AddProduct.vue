<template>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Ajouter un produit</h5>
                <div>
                    <router-link :to="{name: 'products'}" class="btn btn-success">Retour</router-link>
                </div>
            </div>

            <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{strSuccess}}</strong>
            </div>

            <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{strError}}</strong>
            </div>


            <form @submit.prevent="addproduct" enctype="multipart/form-data">
                <div class="form-group mb-2">
                    <label>Titre</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="title" placeholder="Entrer titre du produit">
                </div>

                <div class="form-group mb-2">
                    <label>Description</label><span class="text-danger"> *</span>
                   <textarea class="form-control" rows="3" v-model="description" placeholder="Enter product description"></textarea>
                </div>

                <div class="form-group mb-2">
                    <label>URL</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="url_amazon" placeholder="Entrer url">
                </div>

                <div class="form-gorup mb-2">
                    <label>Image</label><span class="text-danger"> *</span>
                    <input type="file" class="form-control mb-2" v-on:change="onChange">

                    <div v-if="img">
                        <img v-bind:src="imgPreview" width="100" height="100"/>
                    </div>
                </div>

                <div class="form-gorup mb-2">
                    <label>Fichier PDF</label><span class="text-danger"> *</span>
                    <input type="file" class="form-control mb-2" v-on:change="onChangePDF">
                    <embed
                        v-if="filePdf"
                        type="application/pdf"
                        :src="pdfPreview"
                        width="100%"
                        style="max-height: 50rem; min-height: 20rem"
                    />
                </div>
                <button type="submit" class="btn btn-primary mt-4 mb-4"> Ajouter produit</button>
            </form>
        </div>
    </div>
</template>

<script>
export default{
    data() {
        return {
            title: '',
            description: '',
            filePdf: '',
            url_amazon: '',
            img: '',
            strSuccess: '',
            strError: '',
            imgPreview: null,
            pdfPreview: null
        }
    },
    methods: {
        onChange(e) {
            this.img = e.target.files[0];
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.imgPreview = reader.result;
            }.bind(this), false);

            if (this.img) {
                if ( /\.(jpe?g|png|gif)$/i.test( this.img.name ) ) {
                    reader.readAsDataURL( this.img );
                }
            }
        },
        onChangePDF(e) {
            this.filePdf = e.target.files[0];
            this.pdfPreview = URL.createObjectURL(e.target.files[0]);
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.pdfPreview = reader.result;
            }.bind(this), false)

            if (this.filePdf) {
                if ( /\.(pdf)$/i.test( this.filePdf.name ) ) {
                    reader.readAsDataURL( this.filePdf );
                }
            }
        },

        addproduct(e) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                const formData = new FormData();
                formData.append('title', this.title);
                formData.append('description', this.description);
                formData.append('url_amazon', this.url_amazon);
                formData.append('file', this.img);
                formData.append('filePdf', this.filePdf);

                this.$axios.post('/api/products/add', formData, config)
                .then(response => {
                    existingObj.strError = "";
                    existingObj.strSuccess = response.data.success;
                })
                .catch(function(error) {
                    existingObj.strSuccess ="";
                    existingObj.strError = error.response.data.message;
                });
            });
        }

    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}

</script>