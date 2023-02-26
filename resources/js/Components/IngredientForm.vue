<script>
export default {
    name: 'IngredientForm',
    emits: ['ingredients'],
    data() {
        return {
            formRules: [(v) => !!v || 'Please add ingredients'],
            ingredient: null,
            ingredients: [],
        }
    },
    methods: {
        addIngredient() {
            if (this.$refs.form.validate()) {
                this.ingredients.push(this.ingredient);
                this.ingredient = null;
                this.$emit('ingredients', this.ingredients);
            }
        },
        removeIngredient(index) {
            this.ingredients.splice(index, 1);
            this.$emit('ingredients', this.ingredients);
        },
    }
}
</script>

<template>
    <v-form ref="form" @submit.prevent="addIngredient">
        <v-text-field
            v-model="ingredient"
            :rules="formRules"
            label="Add an ingredient"
            placeholder="e.g. Carrot"
            color="grey"
            autofocus
            outlined
            comfortable />
    </v-form>
    <v-chip-group
        column
    >
        <v-chip
            v-for="(ingredient, index) in ingredients"
            :key="index"
            closable
            @click:close="removeIngredient(index)"
        >
            {{ ingredient }}
        </v-chip>
    </v-chip-group>
</template>
