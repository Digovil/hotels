// src/types/hotel.ts

export interface HotelAttribute {
  name: string;
  address: string;
  city: string;
  tax_id: string;
  total_rooms: number;
  created_at: string;
}

export interface Hotel {
  type: string;
  id: string;
  attribute: HotelAttribute;
}

export interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

export interface ApiResponse {
  status: boolean;
  message: string;
  data: {
    current_page: number;
    data: Hotel[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
  };
}