<script setup lang="ts">
import { computed } from 'vue'
import type { Pokemon } from '../types/battle'

const props = defineProps<{
  pokemon: Pokemon
  isWinner: boolean
}>()

const spriteUrl = computed(() => {
  return props.pokemon.animated_sprite || props.pokemon.sprite
})
</script>

<template>
  <article class="pokemon-card" :class="{ winner: isWinner }">
    <span v-if="isWinner" class="winner-badge">
      WIN
    </span>

    <div class="sprite-area">
      <img
        v-if="spriteUrl"
        :src="spriteUrl"
        :alt="`Imagem do Pokémon ${pokemon.name}`"
        class="pokemon-sprite"
      >
    </div>

    <div class="pokemon-info">
      <h2>{{ pokemon.name }}</h2>

      <div class="hp-box">
        <span>HP</span>
        <strong>{{ pokemon.hp }}</strong>
      </div>

      <div class="types">
        <span
          v-for="type in pokemon.types"
          :key="type"
          class="type"
        >
          {{ type }}
        </span>
      </div>
    </div>
  </article>
</template>

<style scoped>
.pokemon-card {
  position: relative;
  width: 260px;
  padding: 18px;
  border: 4px solid #2b2b2b;
  border-radius: 8px;
  background: #f4f4f4;
  box-shadow: 6px 6px 0 #2b2b2b;
  text-align: center;
}

.pokemon-card.winner {
  background: #fff8d6;
  border-color: #ffcb05;
}

.winner-badge {
  position: absolute;
  top: -18px;
  right: -14px;
  padding: 6px 10px;
  border: 3px solid #2b2b2b;
  border-radius: 6px;
  background: #ffcb05;
  color: #2b2b2b;
  font-weight: 900;
  box-shadow: 3px 3px 0 #2b2b2b;
}

.sprite-area {
  height: 150px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 3px solid #2b2b2b;
  border-radius: 6px;
  background:
    linear-gradient(45deg, #d7f3ff 25%, transparent 25%),
    linear-gradient(-45deg, #d7f3ff 25%, transparent 25%),
    linear-gradient(45deg, transparent 75%, #d7f3ff 75%),
    linear-gradient(-45deg, transparent 75%, #d7f3ff 75%);
  background-color: #ffffff;
  background-size: 22px 22px;
  background-position: 0 0, 0 11px, 11px -11px, -11px 0;
}

.pokemon-sprite {
  width: 120px;
  height: 120px;
  object-fit: contain;
  image-rendering: pixelated;
}

.pokemon-info {
  margin-top: 14px;
}

h2 {
  margin: 0 0 12px;
  color: #2b2b2b;
  font-size: 24px;
  text-transform: capitalize;
}

.hp-box {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 0 auto;
  padding: 8px 12px;
  border: 3px solid #2b2b2b;
  border-radius: 6px;
  background: #ffffff;
  color: #2b2b2b;
}

.hp-box span {
  font-weight: 900;
}

.hp-box strong {
  color: #e53935;
  font-size: 22px;
}

.types {
  display: flex;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 12px;
}

.type {
  padding: 5px 10px;
  border: 2px solid #2b2b2b;
  border-radius: 999px;
  background: #e9e9e9;
  color: #2b2b2b;
  font-size: 13px;
  font-weight: 800;
  text-transform: capitalize;
}
</style>