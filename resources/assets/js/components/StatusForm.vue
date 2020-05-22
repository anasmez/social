<template>
    <div>
        <form @submit.prevent="enviar" v-if="isAuthenticated">
            <div class="card-body border-0">
                <textarea v-model="body"
                          class="form-control border-0 bg-light"
                          name="body"
                          :placeholder="`¿Qué estás pensando, ${currentUser.name}?`">

                </textarea>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary"
                        id="create-status">
                        <i class="fa fa-paper-plane mr-1"></i>
                        Publicar estado
                </button>
            </div>
        </form>
        <div v-else class="card-body">
            <a href="/login">Debes loguearte</a>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                body: "",
            }
        },
        methods:{
            enviar(){
                axios.post('/statuses', {body: this.body})
                .then(res=>{
                    EventBus.$emit('status-created', res.data.data); // ['data'=> ['body'=>'el body']]
                    this.body='';
                })
                .catch(err=>{
                    console.log(err.response.data)
                })
            }
        }
    }
</script>

<style scoped>

</style>