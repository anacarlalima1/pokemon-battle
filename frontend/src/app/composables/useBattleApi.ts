import type { BattlePayload, BattleResponse } from '../types/battle'

interface ApiError {
  status?: number
  data?: {
    message?: string
    errors?: Record<string, string[]>
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

      if (apiError.data?.errors) {
        const firstError = Object.values(apiError.data.errors)[0]?.[0]

        throw new Error(firstError || 'Verifique os dados informados.')
      }

      if (apiError.data?.message) {
        throw new Error(apiError.data.message)
      }

      if (apiError.status === 503) {
        throw new Error('A PokéAPI está indisponível no momento. Tente novamente mais tarde.')
      }

      if (apiError.status === 502) {
        throw new Error('Recebemos uma resposta inesperada da PokéAPI. Tente outro Pokémon.')
      }

      if (apiError.status === 404) {
        throw new Error('Um dos Pokémons informados não foi encontrado.')
      }

      throw new Error('Não foi possível realizar a batalha. Tente novamente.')
    }
  }

  return {
    battlePokemons,
  }
}