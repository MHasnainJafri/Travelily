export interface AdvertisingCampaign {
  id: number
  user_id: number
  title: string
  duration_days: number
  locations: string[]
  age_ranges: string[]
  genders: string[]
  relationships: string[]
  created_at: string
  updated_at: string
}