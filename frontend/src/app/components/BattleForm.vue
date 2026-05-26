<script setup lang="ts">
import { reactive } from 'vue'

defineProps<{
  loading: boolean
}>()

const emit = defineEmits<{
  submit: [pokemonOne: string, pokemonTwo: string]
}>()

const form = reactive({
  pokemonOne: '',
  pokemonTwo: '',
})

function handleSubmit() {
  emit('submit', form.pokemonOne, form.pokemonTwo)
}
</script>

<template>
  <form class="battle-form" @submit.prevent="handleSubmit">
    <input
      v-model="form.pokemonOne"
      type="text"
      placeholder="pikachu"
      autocomplete="off"
      aria-label="Primeiro Pokémon"
    >

    <span class="versus-input">VS</span>

    <input
      v-model="form.pokemonTwo"
      type="text"
      placeholder="charizard"
      autocomplete="off"
      aria-label="Segundo Pokémon"
    >

    <button type="submit" :disabled="loading">
      {{ loading ? 'Batalhando...' : 'Batalhar' }}
    </button>
  </form>
</template>

<style scoped>
.battle-form {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
  margin-top: 24px;
}

input {
  width: 210px;
  padding: 13px 15px;
  border: 3px solid #2b2b2b;
  border-radius: 6px;
  background: #f8f8f8;
  color: #222;
  font-size: 16px;
  outline: none;
}

input:focus {
  border-color: #e53935;
}

.versus-input {
  color: #2b2b2b;
  font-size: 22px;
  font-weight: 900;
}

button {
  padding: 14px 22px;
  border: 3px solid #2b2b2b;
  border-radius: 6px;
  background: #ffcb05;
  color: #2b2b2b;
  cursor: pointer;
  font-size: 16px;
  font-weight: 900;
  box-shadow: 3px 3px 0 #2b2b2b;
}

button:hover {
  transform: translate(1px, 1px);
  box-shadow: 2px 2px 0 #2b2b2b;
}

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
</style>