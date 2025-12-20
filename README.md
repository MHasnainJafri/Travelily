This is a comprehensive **Functional Specification Document** for **Travelily**. It details the project scope, user roles, and the specific modules and functionalities available to each role based on your database schema, API structure, and Figma designs.

---

# **Project Name: Travelily**

### **1. Project Overview**

Travelily is a social travel application designed to bridge the gap between planning, socializing, and booking. It is a multi-role platform where users can plan collaborative trips ("Jams"), share social updates, hire local guides, and book accommodations.

**Core Technology Stack:**

* **Backend:** PHP (Laravel)
* **Database:** MySQL
* **Key Feature:** "Jamboard" – A collaborative trip planning tool.

---

### **2. User Roles**

The system supports three distinct roles. A single user account can potentially hold multiple roles (e.g., a Traveler can also be a Host).

1. **Traveler:** The standard user. They plan trips, post on the feed, and book services.
2. **Travel Guide:** A specialized user who offers local experiences, tours, and can be hired directly by travelers.
3. **Host:** A property owner who lists accommodations (hotels, apartments) for travelers to book.

---

### **3. Module Breakdown by Role**

## **A. Traveler Role**

*The core user experience focused on social interaction and trip planning.*

### **Module 1: Authentication & Onboarding**

* **Functionality:**
* **Registration:** Users sign up with name, email, and password or via social login (Google/Facebook).
* **Interest Selection:** During onboarding, users select travel interests (e.g., "Nature", "Hiking") to personalize their feed and friend suggestions.
* **Identity Verification:** Travelers can upload ID documents (Passport/CNIC) to verify their account for safety.



### **Module 2: Social Feed & Networking**

* **Functionality:**
* **Posts:** Users create posts with text, photos, and videos. They can tag friends and "Check-in" at specific locations.
* **Stories:** Users upload 24-hour stories (images/videos) visible to their followers.
* **Engagement:** Users can Like, Comment (with threaded replies), and Share posts.
* **Travel Buddies:** Users can "Follow" others or add them as "Friends". The system suggests buddies based on mutual interests.



### **Module 3: The "Jamboard" (Collaborative Trip Planner)**

* **Description:** A central workspace where a group of friends plans a trip together.
* **Functionality:**
* **Create Jam:** A user starts a Jam by setting a destination, dates, and inviting friends.
* **Permissions:** The creator sets permissions for invites (e.g., "Can Edit Budget", "Can Add Travelers").
* **Itinerary Timeline:** A visual schedule where the group adds Flights, Hotels, and Activities.
* **Budget & Expenses:**
* *Budget Limit:* Setting a total min/max budget.
* *Expense Tracking:* Logging individual costs (Food, Transport) to see who paid what.


* **Task Management:** Creating shared to-do lists (e.g., "Book Visa", "Buy Sunscreen") and assigning them to specific members.
* **Group Chat:** A dedicated chat room for the trip members to discuss plans.



### **Module 4: Booking & Exploration**

* **Functionality:**
* **Search:** Travelers can search for Trips (Jams), Guides, and Accommodations globally.
* **Book Stays:** Users can browse Host listings, view amenities, and send booking requests.
* **Hire Guides:** Users can book a Guide for a specific date range or book a pre-packaged "Experience" (Tour).



### **Module 5: Profile & Memories**

* **Functionality:**
* **Stats:** Displays "Lily Petals" (Rewards), Followers, and "Trips Completed" counts.
* **Gallery:** A grid of uploaded travel photos.
* **Short Video:** A featured video intro on the profile.
* **Reviews:** Users can see reviews left by others regarding their behavior as a traveler.



---

## **B. Travel Guide Role**

*Professionals who monetize their local knowledge.*

### **Module 1: Guide Profile**

* **Functionality:**
* **Professional Bio:** Highlights expertise and languages spoken.
* **Pricing:** Displays a "Direct Hire Rate" (e.g., $34/day) for travelers who want a personal guide.
* **Ratings:** Shows an aggregate star rating from past clients.



### **Module 2: Experience Management**

* **Functionality:**
* **Create Experience:** Guides create tour packages (e.g., "Louvre Museum Tour") with a description, location, duration, and price range.
* **Manage Availability:** (Future Scope) Setting dates when the tour is active.



### **Module 3: Advertising**

* **Functionality:**
* **Create Ads:** Guides can pay to boost their profile or specific experiences.
* **Targeting:** They select target audiences based on Location, Age, Gender, and Interests (e.g., "Target 18-25 year olds interested in Hiking").



### **Module 4: Booking Management**

* **Functionality:**
* **Receive Requests:** Guides receive notifications when a traveler wants to hire them or book a tour.
* **Action:** They can "Accept" or "Reject" the booking request.



---

## **C. Host Role**

*Property owners listing accommodations for rent.*

### **Module 1: Listing Management**

* **Functionality:**
* **Create Listing:** Hosts upload photos and details of their property (Title, Description, Location, Price per Night).
* **Amenities:** Hosts select available facilities (WiFi, Breakfast, Pool, Gym) using icons.
* **House Rules:** Hosts specify rules (e.g., "No Smoking", "No Pets").
* **Capacity:** Setting maximum guests and number of rooms.



### **Module 2: Reservation Management**

* **Functionality:**
* **Incoming Requests:** A dashboard showing pending booking requests from Travelers.
* **Status Control:** Hosts can Approve or Reject requests based on guest profiles.
* **Calendar:** (Future Scope) visual view of booked dates.



### **Module 3: Wallet & Income**

* **Functionality:**
* **Earnings Dashboard:** Hosts can view their "My Income" wallet to track total earnings from bookings.
* **Payouts:** System to withdraw earnings (via Stripe/Bank Transfer).



---

### **4. Shared/Global Modules**

These features are available across the platform regardless of role.

* **Chat System:** Real-time messaging supporting text, images, voice notes, and location sharing. Includes 1-on-1 chats and Group chats.
* **Notifications:** Alerts for likes, comments, booking requests, and trip invites.
* **Reporting & Safety:** Ability to report users, posts, or listings for scams, harassment, or fraud.