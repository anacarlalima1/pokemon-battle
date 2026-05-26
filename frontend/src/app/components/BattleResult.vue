<script setup lang="ts">
import type { BattleResult } from '~/types/battle'

defineProps<{
  result: BattleResult
}>()
</script>

<template>
  <div class="result-card">
    <span class="small-label">
      {{ result.type === 'draw' ? 'Empate' : 'Resultado' }}
    </span>

    <strong>
      {{ result.type === 'draw' ? 'Empate!' : result.winner }}
    </strong>

    <p>{{ result.message }}</p>
  </div>
</template>

<style scoped>
.result-card {
  width: 240px;
  padding: 26px;
  border-radius: 24px;
  background: white;
  text-align: center;
  box-shadow: 0 18px 45px rgba(0, 0, 0, 0.16);
}

.small-label {
  display: block;
  margin-bottom: 8px;
  color: #777;
  font-size: 13px;
  font-weight: 800;
  text-transform: uppercase;
}

.result-card strong {
  display: block;
  margin-bottom: 12px;
  color: #ef5350;
  font-size: 26px;
  text-transform: capitalize;
}

.result-card p {
  margin: 0;
  color: #333;
  line-height: 1.4;
}
</style>