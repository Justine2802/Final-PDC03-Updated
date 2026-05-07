# Rentdevous — Rental Property Management System

Rentdevous is a full-stack web application built with **Laravel 12** and **Livewire 3** that connects property owners (landlords) with prospective renters. It provides a dual-panel interface: a **renter-facing portal** for discovering and booking properties, and an **admin panel** for managing the entire platform.

---

---

## What the Project Does

Rentdevous solves the problem of finding and managing rental properties by providing:

- **Renters** with a searchable property marketplace where they can view listings, save favorites, make reservations, send inquiries, and leave reviews.
- **Admins** (landlords / platform managers) with a complete back-office panel to manage properties, handle reservations, respond to inquiries, and moderate users and reviews.

The application enforces a full authentication lifecycle including email verification and ID verification before a renter can interact with listings.

---

## Key Features

### Renter Portal
| Feature | Description |
|---|---|
| Registration & Login | Email/password auth with email verification flow |
| Identity Verification | Upload a government ID for admin approval before accessing the platform |
| Explore Listings | Browse all active properties with live search, filters (type, price range, bedrooms, bathrooms), and star rating summaries |
| Property Detail | Full property page with dynamic photo gallery, lightbox viewer, amenities, location, host info, and reviews |
| Favorites | Save and manage favorite properties |
| Reservations | Submit a reservation with move-in/move-out dates; track status (pending → confirmed → cancelled) |
| Inquiries | Send a message directly to the landlord from the property detail page |
| Reviews | Leave a star rating and written review after a stay |
| Account Settings | Update profile info, avatar, address, and password |

### Admin Panel
| Feature | Description |
|---|---|
| Dashboard | Platform-wide stats overview |
| Properties | Full CRUD — list, view detail, toggle active/inactive, soft-delete |
| Users | View all registered renters; manage account status and ID verification |
| Reservations | View and update reservation statuses |
| Inquiries | Read and manage renter inquiries |
| Reviews | Moderate and manage submitted reviews |
| Property Types | Manage categories (Apartment, House, Condo, etc.) |
| Cities / Locations | Manage the region → province → city → barangay address hierarchy |
| Settings | Admin profile and password management |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 12 (PHP 8.2+) |
| Reactive UI | Livewire 3 |
| Frontend Interactivity | Alpine.js |
| Styling | Tailwind CSS |
| Database | MySQL (via XAMPP) |
| File Storage | Laravel local disk (`storage/app/public`) |
| Email | Laravel Mail (SMTP) — welcome and ID verification notifications |
| Build Tool | Vite |

---

## Project Structure

```
app/
├── Livewire/
│   ├── Admin/             # Admin panel components (Dashboard, Properties, Users, Reservations, Inquiries, Reviews, Cities, Settings)
│   ├── Auth/              # Authentication (Login, Register, VerifyEmail, CompleteProfile, PendingVerification)
│   └── Renter/            # Renter portal (Home, Explore, PropertyDetail, Favorites, MyReservations, MyInquiries, MyReviews, AccountSettings)
├── Models/
│   ├── User.php           # Roles: admin / renter; includes ID verification fields
│   ├── Property.php       # Listings with soft deletes
│   ├── Reservation.php / Inquiry.php / Review.php / Favorite.php
│   └── Region / Province / City / Barangay / Address  (PH location hierarchy)
└── Mail/
    ├── WelcomeUser.php
    └── IdentityVerified.php

resources/views/livewire/   # Blade templates for all Livewire components
database/migrations/        # Full schema history
database/seeders/           # AddressSeeder — Philippine region/province/city/barangay data
```

---

## Database Schema (Core Tables)

- **users** — auth, role, status, ID verification, address
- **properties** — title, description, price, bedrooms, bathrooms, area, amenities, status
- **property_images** — multiple images per property
- **property_types** — Apartment, House, Condo, Room, etc.
- **addresses** — linked to properties via region → province → city → barangay
- **reservations** — user ↔ property booking with dates, total price, status
- **inquiries** — user ↔ property messages with status
- **reviews** — star rating + text, soft-deletable
- **favorites** — user ↔ property pivot

---

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL (XAMPP recommended on Windows)

### Installation

```bash
# 1. Clone the repository
git clone <repo-url>
cd Final-PDC03-Updated

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy and configure environment
cp .env.example .env
php artisan key:generate

# 5. Set database credentials in .env
# DB_DATABASE=rentdevous
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Run migrations and seed address data
php artisan migrate
php artisan db:seed

# 7. Link storage for uploaded files
php artisan storage:link

# 8. Build frontend assets
npm run build

# 9. Start the development server
php artisan serve
```

Visit `http://127.0.0.1:8000`.

---

## User Roles

| Role | Access |
|---|---|
| `admin` | Full admin panel at `/admin` |
| `renter` | Renter portal at `/renter` |

New registrations default to the `renter` role. Renters must verify their email, complete their profile, and pass ID verification before accessing the full platform.

---

## License

This project is developed as an academic final project for **PDC03** (Platform Development and Coding).
