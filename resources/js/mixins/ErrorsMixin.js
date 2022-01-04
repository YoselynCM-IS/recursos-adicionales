export default {
    data(){
        return {
            errors: {}
        }
    },
    methods: {
        set_errors(error){
            if (error.response.status === 422) {
                this.errors = error.response.data.errors || {};
            }
        }
    },
}