export default {
    data(){
        return {
            load: false,
            enlaces: []
        }
    },
    methods: {
        // OBTENER TODOS LOS ENLACES
        get_enlaces(libro_id, recurso_id){
            this.load = true;
            axios.get('/libros/get_enlaces', {params: { 
                    libro_id: libro_id, recurso_id: recurso_id 
                }}).then(response => {
                this.enlaces = response.data;
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
    },
}