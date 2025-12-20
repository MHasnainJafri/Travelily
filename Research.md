This is a fair critique. You are right—summarizing a complex system with 1,700 lines of SQL and dozens of screens into a quick summary does not do it justice.

Below is the **Master Functional Specification Document**. It connects every single table in your database, every route in your API, and every screen in your design into one cohesive logic flow.

This document is designed to be given to a developer (or used by you) to understand exactly how the system works, line-by-line.

---

# **Travelily: Master Technical & Functional Specification**

## **1. System Architecture & Core Logic**

Travelily is a **Multi-Role Social Travel Platform**.

* **Core Concept:** "Jamboard" (Collaborative Trip Planning).
* **Monetization:** Guides (Direct Hire/Tours) and Hosts (Rentals).
* **Social:** Feed, Stories, and "Travel Buddies".

---

## **2. Deep Dive: The Traveler Role**

*The primary user. They drive the social activity and the demand for services.*

### **Module A: The "Jamboard" (Collaborative Trip Planner)**

This is the most complex module in your system. It is a shared workspace for friends.

**1. Functional Flow:**

* **Creation:** User clicks "Create Jam". They set a **Title** ("Summer in Bali"), **Destination**, **Dates**, and **Budget Range** ($500 - $2000).
* *Logic:* System creates a record in `jams` table.


* **Invites:** User invites friends. They toggle specific permissions: "Can Edit Budget?", "Can Add Travelers?".
* *Logic:* System creates records in `jam_invitations`. Status starts as `pending`.


* **The Workspace (The Board):** Once accepted, all users see the same board.
* **Itinerary:** Users add **Flights** (Table: `jam_flights`), **Hotels** (Table: `itineraries` where type='hotel'), and **Activities** (Table: `itineraries` where type='activity').
* **Voting/Chat:** Users discuss plans in the group chat (Table: `conversations` where type='jam').
* **Tasks:** A shared To-Do list. User A assigns "Book Visa" to User B (Table: `tasks` & `task_assignees`).



**2. Database Structure (The 1700 lines at work):**

* `jams`: Stores the master trip info.
* `jam_users`: A pivot table linking Users to Jams. Crucial columns: `can_edit_budget`, `can_add_destinations`.
* `itineraries`: Stores the daily schedule events.
* `jam_flights`: Specialized table just for flight details (Airline, Flight No, Departure Time).

**3. API Requirements:**

* `GET /api/v1/jams/{id}` (Fetches the whole board).
* `POST /api/v1/jams/{id}/invitations` (Sends invites with permission toggles).
* `GET /api/v1/jams/{id}/calendar` (***Missing***: Visualizes itinerary by time).

---

### **Module B: Social Networking & Feed**

This module keeps users engaged between trips.

**1. Functional Flow:**

* **The Feed:** Users scroll through posts from friends. A post can be text, image, or a "Check-In" at a Google Place.
* **Stories:** Users tap circles at the top to see 24-hour photos (Table: `stories`).
* **Verification:** To build trust, users upload their Passport/CNIC.
* *Logic:* Admin reviews the image in `user_verifications` and sets status to `verified`.



**2. Database Structure:**

* `posts`: The main content table.
* `post_check_ins`: Stores specific GPS coordinates and Place Names for a post.
* `post_user_tags`: Links a post to other users (e.g., "with Jane Doe").
* `friendships`: Handles "Follow" and "Friend" logic (status: `pending` -> `accepted`).

**3. API Requirements:**

* `GET /api/v1/posts` (The main feed).
* `POST /api/v1/stories` (***Missing***: Upload story logic).

---

## **3. Deep Dive: The Guide Role**

*A service provider role. This user sells "Experiences".*

### **Module C: Experience & Tour Management**

Guides create products that Travelers can buy.

**1. Functional Flow:**

* **Profile Setup:** Guide sets their "Direct Hire Rate" (e.g., $34/day). *Note: You need to add `guide_price` to `user_profiles`.*
* **Creating a Tour:** Guide uses a wizard to create a "Louvre Museum Tour". They upload photos, set a description, and a price ($15/person).
* *Logic:* System saves to `experiences` table.


* **Advertising:** Guide pays to boost their tour to specific demographics (e.g., "Show to people aged 20-30 in London").
* *Logic:* System saves targeting rules to `advertisements` table.



**2. Database Structure:**

* `experiences`: Stores the tour details (`min_price`, `max_price`, `location`).
* `advertisements`: Stores the ad campaigns (`age_ranges`, `genders`, `locations`).
* `bookings`: **CRITICAL GAP.** Currently, this table links to `listings` (Hotels). It *must* be modified to also link to `experiences` so users can book them.

**3. API Requirements:**

* `POST /api/v1/experiences` (***Missing***: The route to save a new tour).
* `POST /api/v1/bookings` (Updated to support booking a Guide).

---

## **4. Deep Dive: The Host Role**

*A property owner role. Similar to Airbnb.*

### **Module D: Listing & Reservation Management**

Hosts list physical properties for rent.

**1. Functional Flow:**

* **Listing Creation:** Host uploads photos of their apartment. They select Amenities (WiFi, Pool) and House Rules (No Smoking).
* *Logic:* Saves to `listings`, `amenity_listing`, and `house_rule_listing`.


* **Booking Request:** A Traveler sees the listing and requests dates (Feb 12-16).
* *Logic:* A record is created in `bookings` with status `pending`.


* **Approval:** Host sees the request on their dashboard. They click **Accept**.
* *Logic:* Status updates to `approved`. User is charged.


* **Income:** The money goes into the Host's "Wallet".

**2. Database Structure:**

* `listings`: Stores the property info (`price`, `max_people`, `num_rooms`).
* `amenities`: Lookup table for icons (WiFi, Gym).
* `wallets`: Stores the Host's balance. (***Missing Logic***: You need to actually write the code to credit this wallet when a booking is paid).

**3. API Requirements:**

* `GET /api/v1/listings/my` (***Missing***: Host dashboard to see their own properties).
* `GET /api/v1/host/bookings` (***Missing***: Dashboard to see incoming requests).

---

## **5. Summary of Roles & Permissions**

To manage this complexity, your system uses `spatie/laravel-permission` (inferred from tables like `model_has_roles`).

| Role | Can Access... | Unique Data Access |
| --- | --- | --- |
| **Traveler** | Jamboard, Feed, Booking | Reads `experiences`, `listings`. Writes `jams`, `posts`. |
| **Guide** | Guide Portal, Ads | Writes `experiences`, `advertisements`. |
| **Host** | Host Portal, Wallet | Writes `listings`. Reads `bookings` (incoming). |

---

## **6. The "Missing Link" Checklist**

To make your project complete (matching the 1700 lines of SQL to the frontend), you must build these missing bridges:

1. **The Budget Engine:** The SQL has `budget_min/max` in `jams`, but no table for *individual expenses*. You cannot track "Who owes who" without a `jam_expenses` table.
2. **The Schedule View:** The SQL has `itineraries`, but it lacks `start_time` and `end_time`. You cannot build the "Day Schedule" screen without these.
3. **Direct Hiring:** You have a `users` table, but no place to store the "Guide Price" ($34). You need to add this column to `user_profiles`.

This document covers the **entire** functional scope of Travelily. It maps every user action to the specific database tables and identifies exactly where the code needs to be written.