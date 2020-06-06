<template>
    <form @submit.prevent="addComment" v-if="isAuthenticated" class="mb-3">
        <div class="d-flex align-items-center">
            <img class="rounded shadow-sm float-left mr-2" width="34px"
                 :src="currentUser.avatar"
                 :alt="currentUser.user_name">
            <div class="input-group">
                        <textarea class="form-control border-0 shadow-sm"
                                  name="comment"
                                  v-model="newComment"
                                  placeholder="Escribe un comentario..."
                                  rows="1"
                                  required
                        ></textarea>
                <div class="input-group-append">
                    <button class="btn btn-primary" dusk="comment-btn">Enviar</button>
                </div>
            </div>
        </div>
    </form>
    <div v-else class="text-center mb-3">
        Debes <a href="/login">autenticarte</a> para poder comentar
    </div>
</template>

<script>
    export default {
        props: {
            statusId:{
                type: Number,
                required: true
            }
        },
        data() {
            return {
                newComment: ''
            }
        },
        methods: {
            addComment() {
                axios.post(`/statuses/${this.statusId}/comments`, {body: this.newComment}).then(respuesta => {
                    this.newComment = '';
                    EventBus.$emit(`statuses.${this.statusId}.comments`, respuesta.data.data);
                })
                    .catch(err => {
                        console.log(err.response.data)
                    })
            }
        }
    }
</script>

<style scoped>

</style>t