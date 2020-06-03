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
            friendshipStatus: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                localFriendshipStatus: this.friendshipStatus
            }
        },
        methods: {
            toggleFriendshipStatus() {
                let method = this.getMethod();

                axios[method](`friendships/${this.recipient.name}`)
                    .then(res => {
                        this.localFriendshipStatus = res.data.friendship_status;
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    })
            },
            getMethod() {
                if (this.localFriendshipStatus === 'pending' || this.localFriendshipStatus === 'accepted') {
                    return 'delete';
                }
                return 'post'
            }
        },
        computed: {
            getText() {
                if (this.localFriendshipStatus === 'pending') {
                    return 'Cancelar solicitud';
                } else if (this.localFriendshipStatus === 'accepted') {
                    return 'Eliminar de mis amigos';
                } else if (this.localFriendshipStatus === 'denied') {
                    return 'Solicitud denegada';
                }
                return 'Solicitar amistad';
            }
        }
    }
</script>

<style scoped>

</style>