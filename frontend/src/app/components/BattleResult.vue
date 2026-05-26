<script setup lang="ts">
import { computed } from 'vue'
import type { BattleResult, Pokemon } from '../types/battle'

const props = defineProps<{
  result: BattleResult
  pokemonOne: Pokemon
  pokemonTwo: Pokemon
}>()

const hpDifference = computed(() => {
  return Math.abs(props.pokemonOne.hp - props.pokemonTwo.hp)
})

const winnerPokemon = computed(() => {
  if (props.result.type === 'draw') {
    return null
  }

  return props.result.winner === props.pokemonOne.name
    ? props.pokemonOne
    : props.pokemonTwo
})

const loserPokemon = computed(() => {
  if (props.result.type === 'draw') {
    return null
  }

  return props.result.winner === props.pokemonOne.name
    ? props.pokemonTwo
    : props.pokemonOne
})
</script>

<template>
  <section class="battle-result">
    <div class="result-header">
      <span class="result-label">
        Resultado da batalha
      </span>

      <strong>
        {{ result.type === 'draw' ? 'Empate!' : `${result.winner} venceu!` }}
      </strong>
    </div>

    <div v-if="result.type === 'draw'" class="result-description">
      <p>
        Os dois Pokémons terminaram empatados com
        <strong>{{ pokemonOne.hp }} HP</strong>.
      </p>
    </div>

    <div v-else-if="winnerPokemon && loserPokemon" class="result-description">
      <p>
        <strong class="winner-name">{{ winnerPokemon.name }}</strong>
        venceu porque possui mais HP que
        <strong>{{ loserPokemon.name }}</strong>.
      </p>

      <div class="comparison-box">
        <div class="comparison-item">
          <span>{{ winnerPokemon.name }}</span>
          <strong>{{ winnerPokemon.hp }} HP</strong>
        </div>

        <div class="difference-badge">
          +{{ hpDifference }} HP
        </div>

        <div class="comparison-item">
          <span>{{ loserPokemon.name }}</span>
          <strong>{{ loserPokemon.hp }} HP</strong>
        </div>
      </div>
    </div>

    <p class="message">
      {{ result.message }}
    </p>
  </section>
</template>

<style scoped>
.battle-result {
  width: min(720px, 100%);
  margin: 36px auto 0;
  padding: 24px;
  border: 4px solid #1f2a44;
  border-radius: 16px;
  background: #ffffff;
  color: #1f2a44;
  text-align: center;
  box-shadow: 8px 8px 0 #1f2a44;
}

.result-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}

.result-label {
  padding: 5px 12px;
  border: 3px solid #1f2a44;
  border-radius: 999px;
  background: #3b4cca;
  color: #ffffff;
  font-size: 12px;
  font-weight: 900;
  text-transform: uppercase;
}

.result-header strong {
  color: #e3350d;
  font-size: 30px;
  text-transform: capitalize;
}

.result-description {
  margin-top: 16px;
}

.result-description p {
  margin: 0;
  font-size: 17px;
  font-weight: 800;
  line-height: 1.4;
}

.winner-name {
  color: #e3350d;
  text-transform: capitalize;
}

.comparison-box {
  margin-top: 18px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
}

.comparison-item {
  min-width: 150px;
  padding: 12px;
  border: 3px solid #1f2a44;
  border-radius: 12px;
  background: #f8f3d4;
}

.comparison-item span {
  display: block;
  font-size: 14px;
  font-weight: 900;
  text-transform: capitalize;
}

.comparison-item strong {
  display: block;
  margin-top: 4px;
  color: #e3350d;
  font-size: 22px;
}

.difference-badge {
  padding: 10px 14px;
  border: 3px solid #1f2a44;
  border-radius: 999px;
  background: #ffcb05;
  color: #1f2a44;
  font-size: 18px;
  font-weight: 900;
  box-shadow: 4px 4px 0 #1f2a44;
}

.message {
  margin: 18px 0 0;
  color: #333;
  font-weight: 800;
  line-height: 1.4;
}

@media (max-width: 760px) {
  .battle-result {
    padding: 20px;
  }

  .result-header strong {
    font-size: 24px;
  }

  .comparison-box {
    flex-direction: column;
  }
}
</style>