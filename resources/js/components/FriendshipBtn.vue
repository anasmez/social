<template>
    <button @click="toggleFriendshipStatus">
        {{ getText }}
    </button>
</template>

<script>
    export default {
        props: {
            recipient: {
                type: Object,
                required: true
            },
        },
        data() {
            return {
                friendshipStatus: ''
            }
        },
        created() {
            axios.get(`/friendships/${this.recipient.name}`)
            .then(res=>{
                this.friendshipStatus=res.data.friendship_status
            })
        },
        methods: {
            toggleFriendshipStatus() {
                this.redirectIfGuest();

                let method = this.getMethod();

                axios[method](`friendships/${this.recipient.name}`)
                    .then(res => {
                        this.friendshipStatus = res.data.friendship_status;
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    })
            },
            getMethod() {
                if (this.friendshipStatus === 'pending' || this.friendshipStatus === 'accepted') {
                    return 'delete';
                }
                return 'post'
            }
        },
        computed: {
            getText() {
                if (this.friendshipStatus === 'pending') {
                    return 'Cancelar solicitud';
                } else if (this.friendshipStatus === 'accepted') {
                    return 'Eliminar de mis amigos';
                } else if (this.friendshipStatus === 'denied') {
                    return 'Solicitud denegada';
                }
                return 'Solicitar amistad';
            }
        }
    }
</script>

<style scoped>

</style>