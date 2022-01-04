<template>
    <div>
        <b-form-group label="Nombre (completo):">
            <b-form-input v-model="form.name" required 
                style="text-transform:uppercase;" :disabled="load"
                placeholder="Ingresa tu nombre" minlength="5"
            ></b-form-input>
        </b-form-group>
        <b-form-group label="Correo electrónico:">
            <b-form-input v-model="form.email" type="email" 
                placeholder="Ingresa tu correo" required :disabled="load"
            ></b-form-input>
        </b-form-group>
    </div>
</template>

<script>
export default {
    props: ['form', 'load']
}
</script>

<style>

</style>