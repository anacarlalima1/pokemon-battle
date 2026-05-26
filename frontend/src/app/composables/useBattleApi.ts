import type { BattlePayload, BattleResponse } from '../types/battle'

interface ApiError {
  data?: {
    message?: string
  }
  message?: string
}

export function useBattleApi() {
  const config = useRuntimeConfig()

  async function battlePokemons(payload: BattlePayload): Promise<BattleResponse> {
    try {
      return await $fetch<BattleResponse>(`${config.public.apiBaseUrl}/battles`, {
        method: 'POST',
        body: payload,
      })
    } catch (error) {
      const apiError = error as ApiError

      const message =
        apiError.data?.message ||
        apiError.message ||
        'Não foi possível realizar a batalha.'

      throw new Error(message)
    }
  }

  return {
    battlePokemons,
  }
}