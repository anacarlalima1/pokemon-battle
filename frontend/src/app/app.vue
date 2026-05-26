<script setup lang="ts">
import { computed, ref } from 'vue'
import BattleForm from './components/BattleForm.vue'
import BattleResult from './components/BattleResult.vue'
import PokemonCard from './components/PokemonCard.vue'
import type { BattleResponse } from './types/battle'

const loading = ref(false)
const errorMessage = ref('')
const battle = ref<BattleResponse | null>(null)

const { battlePokemons } = useBattleApi()

const winnerName = computed(() => battle.value?.result.winner)

async function handleBattle(pokemonOne: string, pokemonTwo: string) {
  errorMessage.value = ''
  battle.value = null

  const firstPokemon = pokemonOne.trim()
  const secondPokemon = pokemonTwo.trim()

  if (!firstPokemon || !secondPokemon) {
    errorMessage.value = 'Informe os nomes dos dois Pokémons.'
    return
  }

  try {
    loading.value = true

    battle.value = await battlePokemons({
      pokemon_one: firstPokemon,
      pokemon_two: secondPokemon,
    })
  } catch (error) {
    errorMessage.value = error instanceof Error
      ? error.message
      : 'Erro inesperado ao realizar a batalha.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <main class="game-page">
    <section class="game-panel">
      <header class="game-header">
        <span class="pokeball">●</span>

        <div>
          <h1>Pokémon Battle</h1>
          <p>Digite dois Pokémons e veja quem vence pelo maior HP.</p>
        </div>
      </header>

      <BattleForm
        :loading="loading"
        @submit="handleBattle"
      />

      <p v-if="errorMessage" class="error-message">
        {{ errorMessage }}
      </p>
    </section>

    <section v-if="battle" class="arena">
      <PokemonCard
        :pokemon="battle.pokemon_one"
        :is-winner="winnerName === battle.pokemon_one.name"
      />

      <div class="vs-box">
        VS
      </div>

      <PokemonCard
        :pokemon="battle.pokemon_two"
        :is-winner="winnerName === battle.pokemon_two.name"
      />
    </section>

    <BattleResult
      v-if="battle"
      :result="battle.result"
    />
  </main>
</template>

<style scoped>
.game-page {
  min-height: 100vh;
  padding: 36px 20px;
  background:
    linear-gradient(#00000022 1px, transparent 1px),
    linear-gradient(90deg, #00000022 1px, transparent 1px),
    linear-gradient(135deg, #e53935 0%, #e53935 48%, #263238 48%, #263238 100%);
  background-size: 28px 28px, 28px 28px, 100% 100%;
  font-family: Arial, Helvetica, sans-serif;
}

.game-panel {
  max-width: 920px;
  margin: 0 auto;
  padding: 24px;
  border: 4px solid #2b2b2b;
  border-radius: 8px;
  background: #ffffff;
  box-shadow: 8px 8px 0 #2b2b2b;
}

.game-header {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 18px;
  text-align: left;
}

.pokeball {
  width: 58px;
  height: 58px;
  display: grid;
  place-items: center;
  border: 4px solid #2b2b2b;
  border-radius: 50%;
  background: linear-gradient(#e53935 0 45%, #2b2b2b 45% 55%, #ffffff 55%);
  color: #ffffff;
  font-size: 20px;
  line-height: 1;
}

h1 {
  margin: 0;
  color: #2b2b2b;
  font-size: 40px;
  letter-spacing: -1px;
}

p {
  margin: 6px 0 0;
  color: #555;
  font-weight: 700;
}

.error-message {
  margin-top: 20px;
  color: #c62828;
  text-align: center;
  font-weight: 900;
}

.arena {
  max-width: 900px;
  margin: 38px auto 0;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 28px;
  flex-wrap: wrap;
}

.vs-box {
  width: 82px;
  height: 82px;
  display: grid;
  place-items: center;
  border: 4px solid #2b2b2b;
  border-radius: 50%;
  background: #ffcb05;
  color: #2b2b2b;
  font-size: 28px;
  font-weight: 900;
  box-shadow: 5px 5px 0 #2b2b2b;
}

@media (max-width: 760px) {
  .game-header {
    flex-direction: column;
    text-align: center;
  }

  h1 {
    font-size: 32px;
  }

  .arena {
    gap: 22px;
  }

  .vs-box {
    width: 68px;
    height: 68px;
    font-size: 22px;
  }
}
</style>