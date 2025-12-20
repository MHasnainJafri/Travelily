export interface User {
  id: number;
  name: string;
  email: string;
  username: string;
  profile_photo: string | null;
  created_at: string;
  updated_at: string;
}



export interface UserDetail {
  id: number
  name: string
  email: string
  username: string
  email_verified_at: any
  two_factor_secret: any
  two_factor_recovery_codes: any
  two_factor_confirmed_at: any
  verified: number
  profile_photo: any
  created_at: string
  updated_at: string
  stripe_id: any
  pm_type: any
  pm_last_four: any
  trial_ends_at: any
  traveled_places_count: number
  received_reviews_count: number
  written_reviews_count: number
  listings_count: number
  boards_count: number
  friends_of_mine_count: number
  friend_of_count: number
  roles: Role[]
  profile: Profile
  interests: Interest[]
  buddy_interests: BuddyInterest[]
  travel_activities: TravelActivity[]
  travel_with_options: TravelWithOption[]
  traveled_places: TraveledPlace[]
  recommended_places: RecommendedPlace[]
  received_reviews: Review[]
  written_reviews: Review[]
  experiences: Experience[]
  listings: Listing[]
  boards: Board[]
}

export interface Role {
  id: number
  name: string
  guard_name: string
  created_at: string
  updated_at: string
  pivot: Pivot
}

export interface Pivot {
  model_type: string
  model_id: number
  role_id: number
}

export interface Profile {
  id: number
  bio: any
  rating: number
  followers_count: number
  petals_count: number
  trips_count: number
  local_expert_place_name: any
  local_expert_google_place_id: any
  short_video: any
  facebook: any
  tiktok: any
  linkedin: any
  user_id: number
  created_at: string
  updated_at: string
}

export interface Interest {
  id: number
  name: string
  created_at: string
  updated_at: string
  pivot: Pivot2
}

export interface Pivot2 {
  user_id: number
  interest_id: number
  created_at: string
  updated_at: string
}

export interface BuddyInterest {
  id: number
  name: string
  created_at: string
  updated_at: string
  pivot: Pivot3
}

export interface Pivot3 {
  user_id: number
  buddy_interest_id: number
  created_at: string
  updated_at: string
}

export interface TravelActivity {
  id: number
  name: string
  created_at: string
  updated_at: string
  pivot: Pivot4
}

export interface Pivot4 {
  user_id: number
  travel_activity_id: number
  created_at: string
  updated_at: string
}

export interface TravelWithOption {
  id: number
  name: string
  created_at: string
  updated_at: string
  pivot: Pivot5
}

export interface Pivot5 {
  user_id: number
  travel_with_option_id: number
  created_at: string
  updated_at: string
}

export interface TraveledPlace {
  id: number
  user_id: number
  place_name: string
  address: string
  google_place_id: any
  coordinates: Coordinates
  rank: number
  created_at: string
  updated_at: string
}

export interface Coordinates {
  type: string
  coordinates: number[]
}

export interface RecommendedPlace {
  id: number
  user_id: number
  place_name: string
  address: string
  google_place_id: string
  coordinates: any
  rank: number
  created_at: string
  updated_at: string
}

export interface Review {
  id: number
  reviewer_id: number
  reviewed_user_id: number
  trip_id: any
  rating: number
  reviewer:User,
  reviewed_user:User

  comment: string
  created_at: string
  updated_at: string
}



export interface Experience {
  id: number
  user_id: number
  title: string
  description: string
  location: string
  start_date: string
  end_date: string
  min_price: string
  max_price: string
  created_at: string
  updated_at: string
}

export interface Listing {
  id: number
  user_id: number
  title: string
  location: string
  status: number
  featured: number
  description: string
  max_people: number
  min_stay_days: number
  num_rooms: number
  price: string
  created_at: string
  updated_at: string
  media: Medum[]
}

export interface Medum {
  id: number
  model_type: string
  model_id: number
  uuid: string
  collection_name: string
  name: string
  file_name: string
  mime_type: string
  disk: string
  conversions_disk: string
  size: number
  manipulations: any[]
  custom_properties: any[]
  generated_conversions: any[]
  responsive_images: any[]
  order_column: number
  created_at: string
  updated_at: string
  original_url: string
  preview_url: string
}

export interface Board {
  id: number
  creator_id: number
  name: string
  destination: string
  start_date: string
  end_date: string
  budget_min: string
  budget_max: string
  num_guests: number
  image: any
  status: string
  num_persons: number
  is_locked: number
  created_at: string
  updated_at: string
}
