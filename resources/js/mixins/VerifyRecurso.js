export default {
    methods: {
        // VEIRICAR SI SE PUEDE EDITAR EL RECURSO
        verify_recurso(recurso){
            if(recurso == 'Enlaces') return false;
            if(recurso == 'Links') return false;
            if(recurso == 'Links Tracks') return false;
            return true;
        }
    }
}