<template>
    <div>
        <form @submit.prevent="enviar">
            <div class="card-body border-0">
                <textarea v-model="body" class="form-control border-0 bg-light" name="body" placeholder="¿Qué estás pensando, Anas?"></textarea>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" id="create-status">Publicar estado</button>
            </div>
        </form>
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