export interface Pokemon {
  name: string
  hp: number
  sprite: string | null
  animated_sprite: string | null
  types: string[]
}

export interface BattleResult {
  type: 'winner' | 'draw'
  winner: string | null
  message: string
}

export interface BattleResponse {
  pokemon_one: Pokemon
  pokemon_two: Pokemon
  result: BattleResult
}

export interface BattlePayload {
  pokemon_one: string
  pokemon_two: string
}