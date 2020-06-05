<template>
    <div @click="redirectIfGuest">
        <transition-group name="status-list-transition" tag="div">
            <status-list-item
                    v-for="status in statuses"
                    :status="status"
                    :key="status.id"
            ></status-list-item>
        </transition-group>
    </div>
</template>

<script>
    import StatusListItem from "./StatusListItem";

    export default {
        components: {
            StatusListItem
        },
        props: {
            url: String
        },
        data() {
            return {
                statuses: []
            }
        },
        mounted() {
            axios.get(this.getUrl)
                .then(res => {
                    this.statuses = res.data.data
                })
                .catch(err => {
                    console.log(err.response.data);
                });
            EventBus.$on('status-created', status => {
                this.statuses.unshift(status);
            });

            Echo.channel('statuses').listen('StatusCreated', e => {
                this.statuses.unshift(e.status);
            });
        },
        computed: {
            getUrl() {
                return this.url || '/statuses';
            }
        }
    }
</script>

<style scoped>
    .status-list-transition-move{
        transition: all 0.8s;
    }
</style>