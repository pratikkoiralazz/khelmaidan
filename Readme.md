# KhelMaidan

> **KhelMaidan** is an all-in-one multi-tenant SaaS platform and automated website builder designed for sports venues (futsal arenas, cricket turfs, and badminton courts) across Nepal. 

Upon registration, **KhelMaidan** instantly provisions a custom-branded, standalone website for each venue equipped with a real-time slot booking engine, native local digital payment integrations (eSewa, Khalti, ConnectIPS), and a counter-management dashboard.

---

## Key Features

### 1. Automated Site Builder & Multi-Tenancy
* **Instant Website Provisioning:** Automatically generates a dedicated booking portal (`venue-name.khelmaidan.com` or custom domain `venue.com.np`) immediately upon venue onboarding—no coding required.
* **No-Code Theme Customizer:** Venue owners can customize primary brand colors, upload logos, set hero banners, display ground photo galleries, and embed Google Maps location pins.
* **Mobile-Responsive UI:** Optimizes public booking pages for fast loading and seamless operation across all smartphone browsers.

### 2. Venue Counter & Slot Management
* **Real-Time Calendar & Counter Sync:** One-tap entry interface for counter staff to log manual phone calls or walk-in cash customers, instantly locking slot availability across all online channels.
* **Dynamic & Off-Peak Pricing Engine:** Allows venue owners to define custom hourly rate tiers based on time and day (e.g., lower off-peak morning rates vs. peak evening floodlight hours and weekend surge pricing).
* **Ghost Booking Protection:** Requires an advance digital deposit via eSewa or Khalti to confirm online reservations, mitigating unpaid ghost bookings and no-shows.
* **Daily Revenue Ledger:** Real-time analytics tracking online deposit payouts, physical cash collections, slot occupancy rates, and peak hour performance.

### 3. Player Experience & Booking Engine
* **Live Slot Availability:** Real-time visual calendar displaying active, reserved, and locked time slots filtered by ground type (e.g., 5v5 vs 7v7 futsal, single vs double badminton courts).
* **Digital Booking Pass:** Generates a digital booking receipt with a QR verification code upon successful deposit payment.
* **Monthly Slot Subscriptions:** Allows regular teams to reserve recurring weekly time slots on a fixed monthly pass model.

### 4. Team Matchmaking & Social Network
* **Open Challenge ("Looking for Match"):** Teams that have reserved a slot but lack an opponent can publish an open challenge on the venue's public page.
* **Split-Payment Checkout:** Challenging teams accepting an open fixture can pay their share of the court fee directly online.
* **Tournament Management:** Module to organize local leagues, generate round-robin or knockout brackets, track scores, and collect team registration fees.

---

## Technical Details & Architecture

### System Architecture
The platform utilizes dynamic routing middleware to resolve incoming requests to specific tenant accounts based on the subdomain or custom domain host header.


**27th sept 2026**



Pratik Koirala