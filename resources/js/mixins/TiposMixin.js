export default {
    data(){
        return {
            tipos: [],
            load: false
        }
    },
    created: function(){
        this.get_tipos();
    },
    methods: {
        // OBTENER LOS TIPOS
        get_tipos(){
            this.load = true;
            axios.get('/libros/get_tipos').then(response => {
                this.tipos.push({
                    value: null, text: 'Selecciona una opción', disabled: true
                });
                response.data.forEach(r => {
                    this.tipos.push({ value: r.id, text: `${r.tipo}` });
                });
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
    },
}