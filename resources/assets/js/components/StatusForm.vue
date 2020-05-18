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
        <div v-for="status in statuses" v-text="status.body"></div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                body: "",
                statuses: []
            }
        },
        methods:{
            enviar(){
                axios.post('/statuses', {body: this.body})
                .then(res=>{
                    this.statuses.push(res.data);
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