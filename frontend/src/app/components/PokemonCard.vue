<script setup lang="ts">
import { computed } from 'vue'
import type { Pokemon } from '../types/battle'

const props = defineProps<{
  pokemon: Pokemon
  isWinner: boolean
  hpDifference?: number
}>()

const spriteUrl = computed(() => {
  return props.pokemon.animated_sprite || props.pokemon.sprite
})

const hpPercentage = computed(() => {
  const maxHpReference = 160
  const percentage = (props.pokemon.hp / maxHpReference) * 100

  return Math.min(Math.max(percentage, 5), 100)
})
</script>

<template>
  <article class="pokemon-card" :class="{ winner: isWinner }">
    <div v-if="isWinner" class="winner-badge">
      WIN
    </div>

    <div class="pokemon-screen">
      <img
        v-if="spriteUrl"
        :src="spriteUrl"
        :alt="`Imagem do Pokémon ${pokemon.name}`"
        class="pokemon-sprite"
      >
    </div>

    <div class="pokemon-info">
      <h2>{{ pokemon.name }}</h2>

      <div class="hp-row">
        <span>HP</span>

        <div class="hp-bar">
          <div
            class="hp-fill"
            :style="{ width: `${hpPercentage}%` }"
          />
        </div>

        <strong>{{ pokemon.hp }}</strong>
      </div>

      <p v-if="isWinner && hpDifference" class="hp-difference">
        +{{ hpDifference }} HP
      </p>

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
  width: 280px;
  padding: 14px;
  border: 4px solid #1f2a44;
  border-radius: 14px;
  background: #f8f3d4;
  box-shadow: 8px 8px 0 #1f2a44;
}

.pokemon-card.winner {
  background: #fff0a8;
  border-color: #ffcb05;
}

.winner-badge {
  position: absolute;
  z-index: 2;
  top: -18px;
  right: -14px;
  padding: 6px 12px;
  border: 3px solid #1f2a44;
  border-radius: 8px;
  background: #ffcb05;
  color: #1f2a44;
  font-weight: 900;
  box-shadow: 4px 4px 0 #1f2a44;
}

.pokemon-screen {
  height: 170px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 4px solid #1f2a44;
  border-radius: 10px;
  background:
    linear-gradient(#ffffffcc, #ffffffcc),
    repeating-linear-gradient(
      0deg,
      #b9ecff 0,
      #b9ecff 12px,
      #9eddf5 12px,
      #9eddf5 24px
    );
}

.pokemon-sprite {
  width: 135px;
  height: 135px;
  object-fit: contain;
  image-rendering: pixelated;
  transform: scale(1.15);
}

.pokemon-info {
  margin-top: 12px;
  padding: 12px;
  border: 3px solid #1f2a44;
  border-radius: 10px;
  background: white;
  text-align: center;
}

h2 {
  margin: 0 0 10px;
  color: #1f2a44;
  font-size: 26px;
  text-transform: capitalize;
}

.hp-row {
  display: grid;
  grid-template-columns: auto 1fr auto;
  align-items: center;
  gap: 8px;
  color: #1f2a44;
  font-weight: 900;
}

.hp-bar {
  height: 12px;
  border: 2px solid #1f2a44;
  border-radius: 999px;
  background: #ddd;
  overflow: hidden;
}

.hp-fill {
  height: 100%;
  background: #39d353;
}

.hp-row strong {
  color: #e3350d;
}

.hp-difference {
  display: inline-block;
  margin: 10px 0 0;
  padding: 5px 10px;
  border: 2px solid #1f2a44;
  border-radius: 999px;
  background: #ffcb05;
  color: #1f2a44;
  font-size: 13px;
  font-weight: 900;
}

.types {
  display: flex;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 12px;
}

.type {
  padding: 5px 11px;
  border: 2px solid #1f2a44;
  border-radius: 999px;
  background: #3b4cca;
  color: #fff;
  font-size: 12px;
  font-weight: 900;
  text-transform: uppercase;
}
</style>