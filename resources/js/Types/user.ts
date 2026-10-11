export type UserRole = 'admin' | 'user'

export interface AuthUser {
    id: number
    first_name: string
    last_name: string
    username: string
    role: UserRole
}

export interface User {
    id: number
    first_name: string
    last_name: string
    code: string
    username: string
    role: UserRole
    created_at: string
}

export interface PaginatedData<T> {
    data: T[]
    current_page: number
    last_page: number
    per_page: number
    total: number
}
