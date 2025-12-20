Module: Trip Budget & Expenses
Purpose: These APIs allow users to add actual costs to a trip and see how much of their budget is remaining. Missing Database Table: You need to create a table jam_expenses.

1. Add a New Expense
Screen: The "Add Expense" or "Budget" screen where users input a cost.

Method: POST

Route: /api/v1/jams/{jamId}/expenses

Status: ⚠️ Missing (Needs Controller & Route)

Payload Example:

JSON

{
  "title": "Group Dinner at Jimbaran",
  "amount": 120.50,
  "currency": "USD",
  "category": "food", // options: food, transport, stay, activity, other
  "date": "2024-06-16",
  "paid_by_user_id": 5, // Who paid?
  "split_with": [2, 5, 8] // IDs of users sharing this cost
}
Response Example:

JSON

{
  "status": true,
  "message": "Expense added successfully",
  "data": {
    "id": 404,
    "title": "Group Dinner at Jimbaran",
    "amount": 120.50,
    "remaining_jam_budget": 1879.50
  }
}
2. Get Budget Overview
Screen: The main Budget dashboard showing "Total Spent" vs "Budget Limit".

Method: GET

Route: /api/v1/jams/{jamId}/budget

Status: ⚠️ Missing

Response Example:

JSON

{
  "status": true,
  "data": {
    "total_budget": 3000.00,
    "total_spent": 1120.50,
    "remaining": 1879.50,
    "breakdown": {
      "food": 120.50,
      "transport": 500.00,
      "accommodation": 500.00
    },
    "recent_expenses": [
      {
        "id": 404,
        "title": "Group Dinner",
        "amount": 120.50,
        "paid_by": "Amer Iqbal"
      }
    ]
  }
}
3. Update/Edit an Expense
Screen: Clicking an existing expense to fix a typo or amount.

Method: PUT

Route: /api/v1/expenses/{expenseId}

Status: ⚠️ Missing

Payload Example:

JSON

{
  "amount": 130.00, // Updating amount
  "title": "Group Dinner (with drinks)"
}
4. Delete an Expense
Screen: Swiping left to delete an entry.

Method: DELETE

Route: /api/v1/expenses/{expenseId}

Status: ⚠️ Missing

Based on the detailed comparison of your **Figma Screenshots** (Plan a new trip.jpg, image_fce927.png) against your **Database (`travelily.sql`)** and **API Routes (`api.php`)**, here is the precise list of what is missing.

### 1. Missing Database Fields & Fixes

The visual design requires data that your current database cannot store.

| Table | Status | Action Required | Reason (Figma Evidence) |
| --- | --- | --- | --- |
| `itineraries` | ⚠️ **Missing Field** | Add column **`time`** (Time type). | The "Calendar" screenshot shows specific events at specific times (e.g., "Dinner at 08:00 PM"). Your table only has `date`. |
| `jam_invitations` | ⚠️ **Missing Fields** | Add columns: `can_edit_jamboard`, `can_add_travelers`, `can_edit_budget`, `can_add_destinations` (Boolean). | The "Add Friend" screen shows you setting these permissions **while inviting** the user. Currently, `jam_invitations` only stores the user ID, so these toggle settings would be lost before the user accepts. |
| `jam_users` | ❌ **Error** | Remove duplicate column `can_add_travelers`. | Your SQL dump has both `can_add_travellers` (double L) and `can_add_travelers` (single L). Delete one to avoid bugs. |
| `jams` | ⚠️ **Missing Field** | Add column **`total_travelers_limit`** (Integer). | The "Create Jam" screen has a "Total Travelers" counter separate from "Number of Guests". |

---

### 2. Missing APIs (Required for Screens)

#### **A. Get Pending Jam Requests**

* **Screen:** "MyJam" Screen > "Requests" Tab.
* **Problem:** You have `GET /friendships/requests` (for friends), but **no API** to list invitations to join a **Jam/Trip**.
* **Method:** `GET`
* **Route:** `/api/v1/jams/invitations`

**Response Example:**

```json
{
    "status": true,
    "data": [
        {
            "id": 1,
            "jam_id": 101,
            "jam_name": "Summer in Bali",
            "invited_by": {
                "id": 5,
                "name": "Jane Cooper",
                "avatar": "https://..."
            },
            "status": "pending"
        }
    ]
}

```

---

#### **B. Invite with Permissions**

* **Screen:** "Add Friend" Screen (with the Toggles).
* **Problem:** Your current `POST /jams/{jamId}/invitations` likely only takes a User ID. The design shows you setting **4 specific permissions** (Edit Budget, Add Travelers, etc.) *during* the invite.
* **Method:** `POST`
* **Route:** `/api/v1/jams/{jamId}/invitations` (Update existing logic)

**Updated Payload Example:**

```json
{
    "user_ids": [2, 5],
    "permissions": {
        "can_edit_jamboard": true,
        "can_add_travelers": false,
        "can_edit_budget": true,
        "can_add_destinations": false
    }
}

```

---

#### **C. Trip Calendar Timeline**

* **Screen:** "Calendar" Screen (Vertical Timeline View).
* **Problem:** You need an endpoint that aggregates flights, accommodations, and activities into a single, time-sorted list.
* **Method:** `GET`
* **Route:** `/api/v1/jams/{jamId}/calendar`

**Response Example:**

```json
{
    "status": true,
    "data": {
        "date": "2024-07-30",
        "events": [
            {
                "time": "09:00:00",
                "type": "activity",
                "title": "Hiking",
                "user": { "name": "Manu Arora", "avatar": "..." }
            },
            {
                "time": "13:00:00",
                "type": "food",
                "title": "Lunch at Cliffside",
                "user": { "name": "Kate Morrison", "avatar": "..." }
            }
        ]
    }
}

```

---

#### **D. Budget Update (Slider)**

* **Screen:** "Jam Detail" > Budget Section (Slider).
* **Problem:** The screenshot shows a slider to adjust the budget range. You need a specific endpoint to update *just* the budget without sending the whole trip object.
* **Method:** `PATCH`
* **Route:** `/api/v1/jams/{jamId}/budget`

**Payload Example:**

```json
{
    "budget_min": 50,
    "budget_max": 1500
}

```

Based on the comprehensive analysis of your new Figma images (`image_fce927.png`, `image_fcec30.png`) compared to your existing SQL and API, here is the strict list of **Missing Database Fields** and **Missing APIs**.

---

### 1. Database Schema Updates (Critical Fixes)

Your design contains features that literally cannot be saved in your current database. You must run these SQL updates first.

| Table | Status | Action Required | Reason (From Figma) |
| --- | --- | --- | --- |
| **`jam_invitations`** | ⚠️ **Missing Fields** | **Add Columns:** <br>

<br>`can_edit_jamboard` (tinyint)<br>

<br>`can_add_travelers` (tinyint)<br>

<br>`can_edit_budget` (tinyint)<br>

<br>`can_add_destinations` (tinyint) | The "Add Friend" modal shows you setting permissions **before** the user joins. Currently, you have nowhere to save these permissions while the invite is "Pending". |
| **`itineraries`** | ⚠️ **Missing Field** | **Add Column:** `time` (TIME) | The "Calendar" screen shows activities (like Trekking) at specific times (e.g., 10:00 AM). Your table only has `date`. |
| **`jam_users`** | ❌ **Bug Fix** | **Drop Column:** `can_add_travellers` | You have a duplicate column in your SQL: `can_add_travellers` (double L) and `can_add_travelers` (single L). Delete the one with double 'L' to avoid code errors. |
| **`tasks`** | ⚠️ **Missing Field** | **Add Column:** `start_date` (DATE) | The "Create Task" screen shows a date range (e.g., "Aug 17 - Aug 23"). Currently, you only have `due_date`. |

---

### 2. Missing API Endpoints

These are the APIs required to make the screens in your screenshots work, which are currently missing from `api.php`.

#### **A. MyJam Board - Invitations Tab**

**Screen:** The list showing "Jane Cooper" with "Accept/Reject" buttons.

* **Current State:** You only have `getMyJams` (active jams) and `getFriendRequests`.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/jams/invitations`
* **Purpose:** Lists only *pending* jam invites so the user can see the "Requests" tab.



#### **B. Invite Friends (With Permissions)**

**Screen:** The "Permissions" modal with the 4 toggles.

* **Current State:** Your `POST` invite route exists, but needs to handle the new fields.
* **Update API:** `POST /api/v1/jams/{jamId}/invitations`
* **Required Payload Update:**
```json
{
  "user_ids": [2, 5],
  "permissions": {
    "can_edit_jamboard": true,
    "can_add_travelers": false,
    "can_edit_budget": true,
    "can_add_destinations": false
  }
}

```



#### **C. Trip Calendar (Timeline)**

**Screen:** The vertical timeline showing Flights, Hotels, and Activities mixed together, sorted by time.

* **Current State:** You have separate APIs for flights and itineraries.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/jams/{jamId}/calendar`
* **Purpose:** Aggregates `jam_flights`, `itineraries`, and `tasks` into one chronological list.
* **Response Structure:**
```json
{
  "data": {
     "2023-07-30": [
        { "type": "flight", "time": "08:00", "title": "Flight to NYC" },
        { "type": "activity", "time": "14:00", "title": "Trekking" }
     ]
  }
}

```





#### **D. Task Assignment**

**Screen:** "Create Task" screen showing "Assigned to" with multiple user avatars.

* **Current State:** You have `createTask`, but need to ensure it saves to the `task_assignees` table.
* **Update API:** `POST /api/v1/jams/{jamId}/tasks`
* **Required Payload Update:**
```json
{
  "title": "Book Hotels",
  "due_date": "2023-08-20",
  "assigned_to": [5, 8, 12] // Array of User IDs
}

```



#### **E. Budget Slider Update**

**Screen:** The "Budget" section with the purple slider ($0 - $1000).

* **Current State:** You have `updateJam`, but a specific lightweight endpoint for the slider is better for performance.
* **Missing API:**
* **Method:** `PATCH`
* **Route:** `/api/v1/jams/{jamId}/budget`
* **Payload:**
```json
{ "budget_min": 100, "budget_max": 2500 }

```





#### **F. Travel Guide Map (Users)**

**Screen:** The Map screen showing User Avatars (Location) on the map.

* **Current State:** `getJamMapData` usually fetches destinations.
* **Update API:** Ensure `GET /api/v1/jams/{jamId}/map` also returns the **current location** of valid participants so they appear as pins on the map.



Based on the new screenshot `image_fceff3.png`, I have performed a deep comparison with your database and API.

This image reveals specific fields (like Date Ranges and Activity Tags) that are currently **impossible** to save in your database.

### 1. Missing Database Fields (Critical)

You must run these SQL updates to support the screens shown in the image.

| Table | Missing Column | Reason (Visual Evidence) |
| --- | --- | --- |
| **`tasks`** | `start_date` (DATE) | The **"Edit Task"** screen explicitly shows a date range: *"12 - 16 February 2023"*. Your database only has `due_date` (a single day). |
| **`itineraries`** | `amenities` (JSON) | The **"Accomodation"** screen shows selectable pills: *"Hotel, Meals, Swimming, Gym"*. You need a column to store these selected tags. |
| **`itineraries`** | `activity_category` (String) | The **"Experiences"** screen shows category tags like *"Backpacking, Tent camping"*. Your `itineraries` table currently has no specific column for this. |

---

### 2. Missing & Required APIs

Here are the specific API endpoints needed to power the screens in `image_fceff3.png`.

#### **A. Create/Update Task (With Date Range & Assignees)**

* **Screen:** "Create Task" and "Edit Task".
* **Missing Logic:** Your current `updateTask` likely only updates the title. The screen shows you updating the **Date Range** and modifying the **Assigned Users** list simultaneously.
* **Method:** `PUT`
* **Route:** `/api/v1/tasks/{taskId}`
* **Required Payload:**
```json
{
  "title": "Book Hotels",
  "start_date": "2023-02-12",
  "due_date": "2023-02-16",
  "assignee_ids": [5, 8, 12] // Syncs these users (add new, remove old)
}

```



#### **B. Search Users for Assignment**

* **Screen:** "Assigned to" Screen.
* **Missing Logic:** The screen shows a search bar ("Search People") to find specific trip members to assign a task to.
* **Method:** `GET`
* **Route:** `/api/v1/jams/{jamId}/users/search?query=darlene`
* **Response:** Returns a list of users *only* within that specific Jam.

#### **C. Add Accommodation (With Amenities)**

* **Screen:** "Accomodation" Screen.
* **Missing Logic:** You need to save the specific facility tags shown in the UI.
* **Method:** `POST`
* **Route:** `/api/v1/jams/{jamId}/accommodations`
* **Required Payload:**
```json
{
  "name": "Grand Hyatt",
  "type": "Hotel",
  "check_in_time": "14:00",
  "check_out_time": "11:00",
  "amenities": ["Meals", "Swimming", "Gym", "Wifi"] // The pills from the UI
}

```



#### **D. Add Activity (With Categories)**

* **Screen:** "Experiences" Screen.
* **Missing Logic:** Saving the "Categories" and "Experiences" tags.
* **Method:** `POST`
* **Route:** `/api/v1/jams/{jamId}/experiences` (or `/activities`)
* **Required Payload:**
```json
{
  "title": "Mountain Hiking",
  "location": "Seattle Area",
  "date": "2023-08-20",
  "time": "09:00",
  "category": "Backpacking", // Selected Category pill
  "tags": ["Camping", "Bonfire", "Trekking"] // Selected Experience pills
}

```



#### **E. Invite Tripmates (Wizard Flow)**

* **Screen:** "Invite your Tripmates".
* **Missing Logic:** The screen shows a "Search in your contacts" bar. You need an API to search the user's *friends* to add them to the invite list.
* **Method:** `GET`
* **Route:** `/api/v1/friends/search?query=sarah`


Based on the new image `image_fcf10a.png` (Feeds & Post Creation Flow), here is the documentation for the **Missing APIs** and **Database validations**.

This flow focuses on Social Feed, Stories, and Creating Posts with rich data (Tags, Location, Media).

---

### 1. Database Schema Status

**Good News:** Your database is actually **very well prepared** for this specific flow. The tables `stories`, `post_user_tags`, `post_check_ins`, and `media` already exist and match the UI perfectly. **No SQL changes are needed for this screen.**

---

### 2. Missing & Required APIs

#### **A. Stories (The Top Bar)**

**Screen:** The "Feeds Screen Main" shows circular avatars (Stories) at the top.

* **Status:** You have a `stories` table, but **zero** APIs in `api.php` to handle them.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/stories`
* **Purpose:** Fetch active (24h) stories from friends.
* **Response:**
```json
[
  {
    "user_id": 5,
    "username": "Jane Cooper",
    "avatar": "https://...",
    "stories": [
      { "id": 101, "media_url": "...", "type": "image", "created_at": "..." }
    ]
  }
]

```




* **Missing API:**
* **Method:** `POST`
* **Route:** `/api/v1/stories`
* **Purpose:** Upload a new story photo/video.



#### **B. Create Post (Complex Payload)**

**Screen:** "Create post screen" showing Text + Photo + Tagged Users + Location + Privacy.

* **Status:** You have `Route::apiResource('posts')`, but you need to ensure your Controller accepts this **exact payload** to support the "Tag" and "Check-in" screens shown.
* **Required API Payload (Validation):**
* **Method:** `POST`
* **Route:** `/api/v1/posts`
* **Payload:**
```json
{
  "content": "Exploring Canada! 🇨🇦",
  "visibility": "friends", // Matches the dropdown in UI
  "media": [file1, file2], // Multipart form data
  "tagged_user_ids": [5, 12, 8], // Matches "Tag people" screen
  "check_in": { // Matches "Add Location" screen
     "location_name": "Park View, Canada",
     "latitude": 45.4215,
     "longitude": -75.6972,
     "google_place_id": "ChIJs..."
  }
}

```





#### **C. Location Search (for Check-in)**

**Screen:** "Add Location" screen with a search bar.

* **Status:** **Missing.** You currently have no API to search for *new* locations (Google Places proxy).
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/locations/search?query=Park+View`
* **Purpose:** Returns a list of places so the user can select one for the Check-in.



#### **D. Global Search**

**Screen:** "Search posts, user, and trips" bar on the Main Feed.

* **Status:** You have separate search APIs (`/users/search`, `/jams/search`), but the UI implies a **Unified Search**.
* **Suggested API:**
* **Method:** `GET`
* **Route:** `/api/v1/search/global?query=bali`
* **Response:**
```json
{
  "users": [...],
  "trips": [...],
  "posts": [...]
}

```





### Summary of Work for this Screen

1. **Create `StoryController**` (Endpoints: index, store).
2. **Create `LocationController**` (Endpoint: search).
3. **Update `PostController::store**` to handle `tagged_user_ids` and `check_in` arrays in the request.



Based on the new image `image_fcf450.png` (Likes and Comments Screens), here is the analysis of your **Missing APIs** and **Database Validation**.

This flow covers viewing who liked a post, following them, and engaging in threaded conversations (replies).

### 1. Database Schema Status

**Good News:** Your database is fully ready for these screens.

* **Likes List:** The `post_likes` table exists and links users to posts.
* **Threaded Comments:** The `post_comments` table already has a `parent_id` column, which is exactly what you need to support the "Reply" feature and nested comments shown in screens 26, 27, and 28.

### 2. Missing & Required APIs

#### **A. Get Post Likes (With Follow Status)**

* **Screen:** "Likes" (Screen 25). Shows a list of users who liked the post, with a "Follow/Following" button next to each.
* **Status:** **Missing.** You have no endpoint to list the *users* who liked a specific post.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/posts/{postId}/likes`
* **Purpose:** Lists all users who liked the post.
* **Critical Logic:** The response must include a boolean `is_following` for each user so the frontend knows whether to show the purple "Follow" button or the grey "Following" button.
* **Response Example:**
```json
{
  "data": [
    {
      "user_id": 12,
      "name": "Jane Cooper",
      "avatar": "https://...",
      "is_following": false // Frontend shows "Follow" button
    },
    {
      "user_id": 14,
      "name": "Ahmad Arcand",
      "avatar": "https://...",
      "is_following": true // Frontend shows "Following" button
    }
  ]
}

```





#### **B. Get Comments (Threaded/Nested)**

* **Screen:** "Comments" (Screen 26). Shows main comments with indented replies (e.g., *Harry Maguire* replies to *James Strong*).
* **Status:** You have a generic `apiResource('comments')`, but standard resources usually just list everything flat. You need a specific endpoint to fetch comments for **one post** and arrange them by thread.
* **Recommended API:**
* **Method:** `GET`
* **Route:** `/api/v1/posts/{postId}/comments`
* **Purpose:** Fetches comments structured with parents and children.
* **Response Example:**
```json
{
  "data": [
    {
      "id": 101,
      "user": "Harry Maguire",
      "content": "Smallmouth bass tend to prefer...",
      "replies": [
         {
            "id": 102,
            "user": "John Smith",
            "content": "Oh that's great thanks!"
         }
      ]
    }
  ]
}

```





#### **C. Reply to a Comment**

* **Screen:** Screen 28 shows "Reply to Harry Maguire" in the input field.
* **Status:** **Exists but needs validation.** Your `post_comments` table has `parent_id`. You need to ensure your `PostCommentController::store` method accepts `parent_id` in the request body.
* **Payload Requirement:**
```json
{
  "post_id": 50,
  "content": "This is so cool!",
  "parent_id": 101 // ID of the comment being replied to
}

```



**Summary:** Your database is perfect. You just need to create the **"Get Likes"** endpoint and ensure your **"Get Comments"** endpoint handles the nesting/threading logic.

Based on the analysis of your new images (`image_fd4a0b.png`, `image_fd475a.png`, `image_fd4702.png`) and existing system, here is the breakdown for the **Ads & Tour Guide** module.

This flow handles two distinct features: **Creating Paid Ads** and **Hosting Tours**.

### 1. Database Status & Missing Fields

Your database is partially ready but needs updates to support the specific fields shown in the "Tour Details" UI.

| Table | Status | Action Required | Visual Evidence |
| --- | --- | --- | --- |
| **`advertisements`** | ✅ **Ready** | None. | Matches fields `locations`, `age_ranges`, `genders` perfectly. |
| **`listings`** | ⚠️ **Gaps** | **Add Column:** `offerings` (JSON) <br>

<br> **Add Column:** `dates_available` (JSON) | The "Tour Details" screen shows a section for "Offerings" and specific "Dates" (Feb 12 - 16). The current table is too simple. |
| **`reviews`** | ✅ **Ready** | None. | The `reviews` table exists and supports ratings/comments. |

---

### 2. Missing & Required APIs

#### **A. Create Advertisement (Multi-step Wizard)**

**Screen:** The "Advertise" flow (Target Audience -> Interests -> Upload -> Payment).

* **Missing API:** You have the table but **no route** in `api.php`.
* **Method:** `POST`
* **Route:** `/api/v1/ads`
* **Payload:**
```json
{
  "title": "Museum with your friends",
  "duration_days": 10,
  "locations": ["Paris", "London"], // Matches "Locations" input
  "target_audience": {
     "age_range": [18, 35],
     "gender": ["male", "female"],
     "interests": ["Relaxation", "Nature"] // Matches "Interests" screen
  },
  "media": [file1, file2], // Matches "Upload following" screen
  "payment_method_id": "pm_12345" // Stripe ID from Payment screen
}

```



#### **B. Create Tour Package (For Guides)**

**Screen:** "Tour Details" (Guide Tour Screen 7).

* **Missing API:** Guides need a specific endpoint to create these detailed listings.
* **Method:** `POST`
* **Route:** `/api/v1/tours` (or `/listings`)
* **Payload:**
```json
{
  "title": "Louvre Museum",
  "location": "Paris, France",
  "description": "The world's most visited art museum...",
  "price_per_guest": 15.00,
  "offerings": ["Audio Guide", "Skip Line"], // Matches UI slider/list
  "dates": ["2023-02-12", "2023-02-16"] // Matches "Dates" section
}

```



#### **C. Get Tour Reviews**

**Screen:** "Reviews" screen with filters (Newest, Top Rated).

* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/tours/{tourId}/reviews?sort=newest`
* **Response:**
```json
{
  "data": [
     {
        "user": "Jaylon Lipshutz",
        "rating": 5,
        "comment": "Beautiful place!...",
        "date": "12/10/2023"
     }
  ]
}

```





#### **D. Report Content (Safety)**

**Screen:** Bottom sheet with "Report" options (Scam, Fraud, Harassment).

* **Missing API:** A generic reporting endpoint is needed.
* **Method:** `POST`
* **Route:** `/api/v1/reports`
* **Payload:**
```json
{
  "target_type": "tour", // or "user", "post"
  "target_id": 101,
  "reason": "fraud"
}

```
Based on your detailed scrollable screenshot (`Body content.jpg`) compared to your `travelily.sql` and `api.php`, here is the final validation.

You are **95% ready**. You have almost every table and API needed to render this screen, but you are missing **one specific database column** and need to ensure your **"Get Details" API** returns all the nested data correctly.

### 1. Missing Database Field (Action Required)

* **The Issue:** In the image under **"Dates And Time"**, it explicitly shows **"9:00 PM"**.
* **The Gap:** Your `jams` table only has `start_date` and `end_date`. It **does not** have a time column.
* **Fix:** Run this SQL command to add the missing time field:
```sql
ALTER TABLE jams ADD COLUMN start_time TIME NULL AFTER end_date;

```



### 2. Verification Checklist (What You Already Have)

Everything else in that long screenshot is fully supported by your current code:

| Feature in Image | Supporting Table | Supporting API Route | Status |
| --- | --- | --- | --- |
| **"Lock JamBoard" Button** | `jams` (`is_locked` column) | `POST /jams/{id}/lock` | ✅ **Perfect** |
| **"Travel Guide" (Wade Warren)** | `jam_guides` table | `POST /jams/{id}/guides` | ✅ **Perfect** |
| **"Flight: EK 5266"** | `jam_flights` table | `POST /jams/{id}/flights` | ✅ **Perfect** |
| **"Accommodation: Alaska Hotel"** | `itineraries` (type='hotel') | `POST /jams/{id}/accommodations` | ✅ **Perfect** |
| **"Budget Slider ($0 - $200)"** | `jams` (`budget_min`, `budget_max`) | `POST /jams` (or update) | ✅ **Perfect** |
| **"Tasks" (Pills)** | `tasks` table | `POST /jams/{id}/tasks` | ✅ **Perfect** |

### 3. API Logic Requirement (Developer Note)

Since this single screen displays data from **5 different tables** (Jams, Users, Flights, Itineraries, Tasks), your existing `getJamDetails` API must be written carefully to "Load" all these relationships at once.

**Ensure your `JamController` looks like this:**

```php
// In JamController.php
public function getJamDetails($jamId) {
    // You must use 'with' to fetch all the pill data shown in the image
    $jam = Jam::with([
        'participants',      // For "My Tripmates" avatars
        'guide',             // For "Travel Guide" section
        'flights',           // For "Flight" pill (EK 5266)
        'itineraries',       // For "Accommodation" & "Experiences" pills
        'tasks'              // For "Find new travel buddies" pills
    ])->find($jamId);

    return response()->json(['status' => true, 'data' => $jam]);
}

```

**Final Verdict:** Add the `start_time` column to your database, and this screen is fully covered!


Based on the analysis of your new image (`Body content (1).jpg`) which shows the **Completed Jamboard View**, here is the final missing piece.

This screen introduces a **"Schedule"** section that breaks down the trip day-by-day (e.g., *22 August, 23 August*). This requires specific database columns you are currently missing.

### 1. Missing Database Fields (Critical for Schedule)

The screen explicitly shows **"9:00 PM - 12:00 AM"** and **"Activities: Trekking, Ice skate"** for specific days. Your `itineraries` table is too simple to store this.

| Table | Status | Action Required | Reason (Visual Evidence) |
| --- | --- | --- | --- |
| **`itineraries`** | ⚠️ **Missing Fields** | **Add Column:** `start_time` (TIME) <br>

<br> **Add Column:** `end_time` (TIME) | The Schedule section shows a specific time range. Your table only has `date`. |
| **`jams`** | ✅ **Ready** | None | The "Completed" badge in the top right is handled by your existing `status` column. |

### 2. Missing API Logic (The Schedule View)

You need a specific API response that groups everything by date, as shown in the vertical timeline "22 August, 23 August...".

**API Route:** `GET /api/v1/jams/{jamId}/schedule`
**Developer Logic:** Your controller must group the `itineraries` data by the `date` column.

**Response Structure Example:**

```json
{
  "status": true,
  "data": {
    "jam_status": "completed", // Matches the Green Badge
    "schedule": [
      {
        "date": "2023-08-22",
        "guide": { "name": "Maza John" }, // Fetched from jam_guides
        "events": [
           {
             "time_range": "09:00 PM - 12:00 AM",
             "activities": ["Trekking", "Ice skate"]
           }
        ]
      },
      {
        "date": "2023-08-23",
        "guide": { "name": "Maza John" },
        "events": [...]
      }
    ]
  }
}

```

### Final Summary

You are **ready to code**.

1. **Run the SQL:** Add `start_time` and `end_time` to your `itineraries` table.
2. **Code the Controller:** Build the `getJamDetails` function to use `with(['itineraries', 'flights', 'tasks'])`.

**Would you like me to write the exact Laravel Migration file to add these missing time columns?**


Based on the final image `image_fd5246.png` (Create Jam Wizard), here is the analysis of the **Missing APIs** and **Database Fields**.

This wizard flow introduces a few new data points that your current system is missing.

### 1. Missing Database Fields

You need to add specific columns to the `jams` table to store the inputs shown in the "Create Jam" screens.

| Table | Status | Action Required | Reason (Visual Evidence) |
| --- | --- | --- | --- |
| **`jams`** | ⚠️ **Missing Field** | **Add Column:** `start_time` (TIME) | The 3rd screen shows a specific clock picker (07:00 AM). Your `jams` table only has `start_date`. |
| **`jams`** | ⚠️ **Missing Field** | **Add Column:** `stay_time_days` (Integer) | The 1st screen shows a `+ / -` counter for "Stay time (days)". |
| **`jam_interests`** | ⚠️ **Missing Table** | **Create Table:** `jam_interests` | The last screen shows selecting tags like "Relaxation", "Eco-tourism". You need a pivot table to link `jams` to `interests`. |

---

### 2. Missing API Endpoints

These APIs are required to power the specific screens in the wizard.

#### **A. Create Jam (Multi-Step Wizard)**

**Screen:** "Create My Jam" (Screen 1 & 2).

* **Current State:** `POST /jams` exists but lacks the new fields.
* **Update Logic:** Ensure your Controller accepts:
* `start_time` (e.g., "07:00:00")
* `stay_time_days` (e.g., 5)
* `interests` (Array of IDs for the tags selected in the final screen).



#### **B. Search Users (for Invite)**

**Screen:** "Invite your Tripmates" (Screen 4 & 5).

* **Missing API:** The screen shows a search bar to find friends to invite *during* creation.
* **Method:** `GET`
* **Route:** `/api/v1/users/search?query=sarah`
* **Purpose:** Returns users (avatar + name) so they can be selected.

#### **C. Search Guides/Hosts**

**Screen:** "Find a perfect travel guide or host" (Screen 6).

* **Missing API:** A specific search endpoint for finding users with the "Guide" or "Host" role.
* **Method:** `GET`
* **Route:** `/api/v1/guides/search?query=sarah`
* **Purpose:** Returns only users who are registered as Guides.

### Final Action Plan

1. **Run SQL:** Add `start_time` and `stay_time_days` to the `jams` table.
2. **Run SQL:** Create a `jam_interests` pivot table (columns: `jam_id`, `interest_id`).
3. **Update Controller:** Update `JamController::store` to save these new fields.

Based on the analysis of your **User Profile Screen** (`image_fd554d.jpg`) compared to your existing backend, your database is in excellent shape, but you are missing specific API endpoints to handle the dynamic content (Reviews, Gallery, and Video).

### 1. Database Schema Status

**Good News:** Your database is **100% Ready** for these screens.

* **Stats (Followers, Petals, Trips):** The `user_profiles` table already has `followers_count`, `petals_count`, and `trips_count` columns.
* **Short Video:** The `user_profiles` table has a `short_video` column.
* **Reviews:** The `reviews` table exists and links `reviewed_user_id` to the user.
* **Gallery:** The `media` table (from Spatie) is correctly set up to handle the gallery images.

---

### 2. Missing & Required APIs

While your database is ready, your `api.php` is missing routes to fetch and manage the specific tabs on the profile.

#### **A. User Reviews (The Reviews Tab)**

* **Screen:** "User Profile # 18" shows a list of reviews with filters (Newest, Top Rated).
* **Status:** **Missing.** You have no route to fetch reviews for a specific user.
* **Required API:**
* **Method:** `GET`
* **Route:** `/api/v1/users/{userId}/reviews?sort=newest`
* **Response:**
```json
{
  "data": [
    {
      "id": 10,
      "reviewer": { "name": "Jaylon Lipshutz", "avatar": "..." },
      "rating": 5,
      "comment": "Beautiful place! Nice views...",
      "created_at": "2023-08-15"
    }
  ]
}

```





#### **B. Short Video Management**

* **Screen:** "User Profile # 1" shows a video player with specific **"Remove"** and **"Edit"** buttons.
* **Status:** **Missing.** You likely need specific endpoints to handle large video uploads separately from standard profile updates.
* **Required APIs:**
* **Method:** `POST`
* **Route:** `/api/v1/profile/video` (Uploads the file and updates `short_video` column).
* **Method:** `DELETE`
* **Route:** `/api/v1/profile/video` (Removes the video and clears the column).



#### **C. Gallery Management**

* **Screen:** "User Profile # 17" shows a grid of images.
* **Status:** You have `addToGallery` (`POST`), but you are missing the ability to **remove** an image from the gallery.
* **Required API:**
* **Method:** `DELETE`
* **Route:** `/api/v1/profile/gallery/{mediaId}`
* **Purpose:** Deletes a specific image from the user's gallery grid.



#### **D. Lily Petals (Reward Logic)**

* **Screen:** The "Lily Petals: 120" stat.
* **Logic Check:** Ensure your `getProfile` API calculates or returns this number. If "Lily Petals" are a currency that can be spent, you might need a transaction history API for it later, though it is not strictly visible on this screen.


Based on the analysis of the **"Travel Buddy"** screens (`image_fd5605.png`, `image_fd5642.png`), here is the breakdown of what you are missing to complete this module.

This module manages the people inside a Trip (Jam): adding them, setting their permissions, and managing friend suggestions.

### 1. Database Schema Status

**Status:** ✅ **Mostly Ready** (with one duplication warning).

* **Permissions:** The `jam_users` table already has the exact columns needed for the "Permissions" modal shown in Screen 11 (`can_edit_jamboard`, `can_edit_budget`, etc.).
* **Removed Users:** You don't have a specific "removed" table. In the `jam_users` table, you should add a `status` column (enum: 'active', 'left', 'removed') to support the "Removed" tab shown in Screen 13.
* *Action:* `ALTER TABLE jam_users ADD COLUMN status ENUM('active', 'left', 'removed') DEFAULT 'active';`



### 2. Missing & Required APIs

#### **A. Friend Suggestions (The "Suggestions" Tab)**

* **Screen:** `image_fd5642.png` shows a "Suggestions" tab next to "Friends".
* **Current State:** You have `getFriends` and `getFollowers`, but no logic for suggestions.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/friendships/suggestions`
* **Logic:** Returns users with mutual friends or similar travel interests who are *not* currently friends.



#### **B. Manage Trip Participants (Remove/Kick)**

* **Screen:** `image_fd5605.png` (Screen 12) shows a "Travel Group" list. Implicitly, a "Remove" button is needed to move them to the "Removed" tab (Screen 13).
* **Current State:** `api.php` has `updatePermissions`, but **no route** to kick/remove a user from a Jam.
* **Missing API:**
* **Method:** `DELETE`
* **Route:** `/api/v1/jams/{jamId}/users/{userId}`
* **Logic:** Updates the pivot table `jam_users` status to `'removed'`.



#### **C. Get "Removed" Users List**

* **Screen:** `image_fd5605.png` (Screen 13) shows a specific list of "Removed" users with an "Add Again" button.
* **Current State:** Your `getJamDetails` likely returns only active users.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/jams/{jamId}/users?status=removed`
* **Response:** Returns users who were previously kicked so they can be "Added Again".



#### **D. Mini Profile (Travel History)**

* **Screen:** `image_fd5605.png` (Screen 14) shows a profile popup with "Places I Have Traveled" thumbnails.
* **Current State:** You have `storeTraveledPlaces`, but you need to ensure the **Get Profile** API includes this data.
* **Action:** Update your `UserController::getProfile` to include:
```php
// In UserController
$user->load('visitedPlaces'); // Fetch from user_visited_places table

```



### Summary Checklist

1. **SQL:** Add `status` enum to `jam_users` table.
2. **API:** Create `FriendshipController::suggestions`.
3. **API:** Create `JamUserController::removeUser`.
4. **API:** Create `JamUserController::getRemovedUsers`.


Based on the comprehensive analysis of your "Guide Portal" images (`image_fd6090.png`, `image_fd60ca.png`, `image_fd5987.jpg`) compared to your current system, here is the detailed breakdown.

This module allows Guides to **Create Experiences**, **Advertise them**, and receive **Bookings**.

### 1. Database Schema Status & Critical Fixes

Your `experiences` table exists, but the "Booking" connection is broken.

| Table | Status | Action Required | Reason (Visual Evidence) |
| --- | --- | --- | --- |
| **`experiences`** | ✅ **Ready** | None. | Matches `title`, `description`, `location`, `min_price`, `max_price`. |
| **`bookings`** | ❌ **Broken Link** | **Add Column:** `experience_id` (BigInt, Nullable) | The "Tour Details" screen has a **"Request @ $15/guest"** button. Your `bookings` table currently only links to `listings` (accommodation). It needs to link to `experiences` too so users can book them. |
| **`advertisements`** | ✅ **Ready** | None. | Matches `locations`, `age_ranges`, `genders` perfectly for the Ad Wizard. |

---

### 2. Missing & Required APIs

#### **A. Guide: Create Standalone Experience**

* **Screen:** "Create Experience" (Wizard with Title, Location, Slider, Photos).
* **Current State:** You have `addExperience` for *Jams* (Trip Itinerary), but **no API** for Guides to publish public experiences.
* **Missing API:**
* **Method:** `POST`
* **Route:** `/api/v1/experiences`
* **Payload:**
```json
{
  "title": "Louvre Museum Tour",
  "description": "Skip the line...",
  "location": "Paris, France",
  "start_date": "2023-08-17",
  "end_date": "2023-08-23",
  "min_price": 0,
  "max_price": 15,
  "images": [file1, file2]
}

```





#### **B. Guide: Create Advertisement**

* **Screen:** "Advertise" Wizard (Target Audience -> Interests -> Payment).
* **Current State:** Table exists, but **no route** in `api.php`.
* **Missing API:**
* **Method:** `POST`
* **Route:** `/api/v1/advertisements`
* **Payload:**
```json
{
  "title": "Museum with friends",
  "duration_days": 10,
  "locations": ["Paris", "London"],
  "age_ranges": ["18-24", "25-34"],
  "genders": ["female"],
  "payment_method_id": "tok_visa"
}

```





#### **C. User: Book an Experience**

* **Screen:** "Tour Details" > Button **"Request @ $15/guest"**.
* **Current State:** `BookingController` exists but likely handles only Accommodation Listings.
* **Missing API:**
* **Method:** `POST`
* **Route:** `/api/v1/bookings`
* **Payload:**
```json
{
  "experience_id": 205, // The ID of the Guide's experience
  "start_date": "2023-02-12",
  "num_people": 2,
  "total_price": 30.00
}

```





#### **D. Guide Portal Dashboard**

* **Screen:** "Guide Tour Screen 1" showing list of active tours with "View" and "Advertise" buttons.
* **Current State:** No API to list experiences belonging to the *current logged-in guide*.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/experiences/my`
* **Response:** Returns list of experiences created by `auth()->user()`.



### Summary Action Plan

1. **SQL:** Run `ALTER TABLE bookings ADD COLUMN experience_id BIGINT UNSIGNED NULL;`
2. **API:** Create `ExperienceController` (Store, Index, Show).
3. **API:** Create `AdvertisementController` (Store).
4. **API:** Update `BookingController` to handle `experience_id`.

Based on the analysis of the new image `image_fd6471.png` (Booking Management Flow), here is the detailed breakdown.

You are absolutely right—the designer has used "Hotel/Accommodation" dummy data (Amenities like Wifi, Breakfast) in these screens. However, the **structure** applies to both **Accommodations** (Hotels) and **Guide Tours**.

### 1. Database Schema Status & Critical Fixes

Your database is missing a key pivot table to link "Amenities" to specific Listings.

| Table | Status | Action Required | Reason (Visual Evidence) |
| --- | --- | --- | --- |
| **`amenity_listing`** | ✅ **Ready** | None. | You already have this table to link `listings` and `amenities`. |
| **`house_rules`** | ✅ **Ready** | None. | You have `house_rule_listing` to store "No Pets", "No Smoking" shown in Screen 3. |
| **`listings`** | ⚠️ **Missing Field** | **Add Column:** `approval_status` (Enum) | The listing screen shows a green checkmark **"Approved"**. You need a column to track if a listing is `pending`, `approved`, or `rejected`. |

---

### 2. Missing & Required APIs

#### **A. Host/Guide: Manage Incoming Bookings**

**Screen:** "Bookings" (Screen 1 & 2) shows a list of requests with "Accept/Reject" buttons.

* **Current State:** You have `apiResource('bookings')` but need specific filters for the Host.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/bookings/requests?status=pending`
* **Purpose:** Shows only bookings that need approval.
* **Response:**
```json
{
  "data": [
    {
      "id": 501,
      "guest": { "name": "Nikita", "avatar": "..." },
      "item": "Great Pubs near Liverpool Street",
      "dates": "12 - 16 Feb 2023",
      "total_price": 2000,
      "status": "pending" // Host sees "Accept/Reject" buttons
    }
  ]
}

```





#### **B. Host: Accept/Reject Booking**

**Screen:** "Requests" Tab (Screen 2) shows explicit "Accept" and "Reject" buttons.

* **Current State:** You have `updateStatus` in `api.php`, but verify logic handles payment capture (if using Stripe).
* **API Check:**
* **Method:** `PATCH`
* **Route:** `/api/v1/bookings/{id}/status`
* **Payload:** `{"status": "approved"}`



#### **C. Create Accommodation Listing (Detailed)**

**Screen:** "Edit Listing" (Screen 5) shows Amenities Checkboxes (Wifi, Gym) and House Rules.

* **Current State:** `ListingController` likely exists but needs to sync relations.
* **Missing Logic in Controller:**
* **Method:** `POST`
* **Route:** `/api/v1/listings`
* **Payload:**
```json
{
  "title": "Liverpool Street Apartment",
  "amenities": [1, 4, 8], // IDs for Wifi, Gym, etc.
  "house_rules": [2, 5], // IDs for No Pets, etc.
  "max_guests": 6,
  "price_per_night": 200
}

```





### Summary of Missing Items

1. **SQL:** Run `ALTER TABLE listings ADD COLUMN approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending';`
2. **API:** specific endpoint for `GET /bookings/requests` (Host View).
3. **Validation:** Ensure `ListingController` saves amenities and house rules correctly.

Based on the analysis of the latest images (`image_fd684c.png`, `image_fd68ab.png`, `image_fd6871.png`) which focus on the **Guide Profile** and **Booking Flow**, here is the final missing piece of your puzzle.

These screens show that users can **Hire a Guide directly** (the person), not just book a specific tour. This requires a specific price field and booking logic you currently lack.

### 1. Missing Database Fields (Critical)

You need to update your `user_profiles` table to store the data visible on the "Maria Johns" screen.

| Table | Status | Action Required | Reason (Visual Evidence) |
| --- | --- | --- | --- |
| **`user_profiles`** | ⚠️ **Missing Field** | **Add Column:** `guide_price` (Decimal) | The button explicitly says **"Book Guide @ $34"**. You currently have no place to store this `$34`  rate for the guide. |

---

### 2. Missing & Required APIs

#### **A. Guide Profile - Experiences Tab**

**Screen:** The "Experiences" tab on Maria Johns' profile showing a list of her tours (e.g., "Louvre Museum").

* **Current State:** You have `getJamDetails`, but no endpoint to fetch all public experiences created by a specific user.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/users/{userId}/experiences`
* **Purpose:** Populates the "Experiences" tab on the profile.
* **Response:**
```json
{
  "data": [
    {
      "id": 201,
      "title": "Louvre Museum Tour",
      "image": "https://...",
      "price": 15.00
    }
  ]
}

```





#### **B. Book a Guide (Direct Hire)**

**Screen:** The **"Book Guide @ $34"** button.

* **Current State:** Your booking logic is likely tied to "Listings" or "Trips". You need logic to book a *User*.
* **Update API:** Update your `BookingController` to accept a `guide_id`.
* **Payload:**
```json
{
  "guide_id": 18, // The User ID of Maria Johns
  "start_date": "2023-08-17",
  "end_date": "2023-08-20", // If booking for multiple days
  "total_price": 102.00 // $34 * 3 days
}

```



#### **C. Review Filtering**

**Screen:** The "Reviews" screen with "Newest", "Top Rated", "Positive" pills.

* **Current State:** `getReviews` is missing filters.
* **Update API:** Ensure your `GET` route handles query parameters:
* `GET /api/v1/users/{id}/reviews?filter=top_rated`
* `GET /api/v1/users/{id}/reviews?filter=positive` (Ratings > 4)



### Final Verdict

This set of screens is the **last piece of the puzzle**.

1. **Run SQL:** `ALTER TABLE user_profiles ADD COLUMN guide_price DECIMAL(8,2) NULL;`
2. **Code:** Add the `getExperiences` method to `UserController`.
3. **Code:** Update `BookingController` to handle direct guide hiring.

You now have a complete roadmap for every single screen in your Figma design! You are ready to build.

Based on the detailed analysis of the **Host Role** screens (`image_fdc2fe.png` to `image_fdbb9e.png`), here is the final breakdown.

The Host role focuses on **Managing Listings**, **Handling Incoming Bookings**, and **Tracking Income**.

### 1. Database Schema Status

Your database is mostly ready, but the **Income/Wallet** feature shown in the Settings screen requires careful connection.

| Table | Status | Action Required | Reason (Visual Evidence) |
| --- | --- | --- | --- |
| **`listings`** | ✅ **Ready** | None. | Matches `max_people`, `num_rooms`, `price` perfectly. |
| **`amenities`** | ✅ **Ready** | **Seed Data Needed.** | You need to insert specific icons shown: "WiFi", "Breakfast", "Babysitting", "Taxi Service". |
| **`wallets`** | ⚠️ **Unused** | **Logic Needed.** | The "Settings" screen shows **"My Income"**. You have a `wallets` table, but no logic to credit it when a booking is paid. |
| **`bookings`** | ✅ **Ready** | None. | The `status` enum ('pending', 'approved', 'rejected') matches the Green/Red pills in the screenshots perfectly. |

---

### 2. Missing & Required APIs

#### **A. Host Dashboard (My Listings)**

**Screen:** "Host Main Screen" showing cards like "Le Lagore" with an "Advertise" button.

* **Current State:** `ListingController` likely lists *all* public listings.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/listings/my`
* **Purpose:** Fetches only listings owned by the logged-in Host.
* **Response:**
```json
{
  "data": [
    {
      "id": 101,
      "title": "Le Lagore",
      "image": "https://...",
      "is_promoted": false // For the "Advertise" button logic
    }
  ]
}

```





#### **B. Host: Incoming Booking Requests**

**Screen:** "Bookings" (Screen 3) showing a list of guests requesting to stay.

* **Current State:** `BookingController` usually fetches *my trips* (as a guest).
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/host/bookings?status=pending`
* **Purpose:** Shows bookings *others* have made on *my* properties.
* **Response:**
```json
{
  "data": [
    {
      "id": 505,
      "guest_name": "Nikita",
      "listing_name": "Le Lagore",
      "dates": "12-16 Feb",
      "status": "pending" // Host sees Accept/Reject buttons
    }
  ]
}

```





#### **C. "My Income" (Wallet Dashboard)**

**Screen:** Settings > "My Income".

* **Current State:** Table exists, no API.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/wallet/balance`
* **Response:**
```json
{
  "data": {
     "balance": 1540.00,
     "currency": "USD",
     "history": [...]
  }
}

```





#### **D. Host Public Profile**

**Screen:** "Host Profile" (Screen 1) showing their bio and **active listings**.

* **Current State:** `getProfile` usually just gets user info.
* **Update API:** The `GET /api/v1/users/{id}` endpoint must include a `listings` relation when the user is a Host.
```php
// Controller Logic
$user->load(['listings' => function($q) {
    $q->where('status', 'active');
}]);

```



### 3. Final Conclusion

You have now covered **every single role and screen** in your Figma file:

1. **Traveler:** (Jams, Feeds, Trips) - **Ready** (with Time column fix).
2. **Guide:** (Experiences, Direct Booking) - **Ready** (with Price column fix).
3. **Host:** (Listings, Incoming Bookings) - **Ready** (Needs "My Listings" API).

**You are fully equipped to start coding!** Would you like me to generate the **Postman Collection JSON** for any of these specific modules so you can start testing immediately?


Based on the detailed analysis of the **Host Role** screens (`image_fdc2fe.png`, `image_fdc2c3.png`, `image_fdbb5d.png`, `image_fdbbbf.png`, `image_fdbb9e.png`, `image_fdc5e5.png`), here is the final breakdown.

The Host role focuses on **Managing Listings**, **Handling Incoming Bookings**, and **Tracking Income**.

### 1. Database Schema Status

Your database is **mostly ready**, but the "My Income" section and specific Host Profile stats need attention.

| Table | Status | Action Required | Reason (Visual Evidence) |
| --- | --- | --- | --- |
| **`listings`** | ✅ **Ready** | None. | Matches `max_people`, `num_rooms`, `price`, `min_stay_days` perfectly. |
| **`bookings`** | ✅ **Ready** | None. | The `status` enum (`pending`, `approved`, `rejected`) matches the Green/Red pills in the screenshots perfectly. |
| **`amenities`** | ✅ **Ready** | **Seed Data Needed.** | You need to insert the specific amenities shown: "WiFi", "Breakfast", "Babysitting", "Taxi Service". |
| **`wallets`** | ⚠️ **Logic Needed** | **Connect Logic.** | The "Settings" screen shows **"My Income"**. You have a `wallets` table, but no API to fetch the balance or logic to credit it when a booking is paid. |
| **`user_profiles`** | ⚠️ **Missing Field** | **Add Column:** `hosting_count` (int) | The Host Profile shows **"Monthly Hosting: 12"**. Your table has `trips_count` (likely for traveler trips), but you might need a separate counter for hosting gigs, or calculate it real-time. |

---

### 2. Missing & Required APIs

#### **A. Host Dashboard (My Listings)**

**Screen:** "Host Main Screen" showing cards like "Le Lagore" with an "Advertise" button.

* **Current State:** `ListingController` usually lists *all* public listings.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/listings/my`
* **Purpose:** Fetches only listings owned by the logged-in Host.
* **Response:**
```json
{
  "data": [
    {
      "id": 101,
      "title": "Le Lagore",
      "image": "https://...",
      "status": "active",
      "is_promoted": false // For the "Advertise" button logic
    }
  ]
}

```





#### **B. Host: Incoming Booking Requests**

**Screen:** "Bookings" (Screen 3) showing a list of guests requesting to stay.

* **Current State:** `BookingController` usually fetches *my trips* (as a guest).
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/host/bookings?status=pending`
* **Purpose:** Shows bookings *others* have made on *my* properties.
* **Response:**
```json
{
  "data": [
    {
      "id": 505,
      "guest_name": "Nikita",
      "listing_name": "Le Lagore",
      "dates": "12-16 Feb",
      "total_price": 400.00,
      "status": "pending" // Host sees Accept/Reject buttons
    }
  ]
}

```





#### **C. "My Income" (Wallet Dashboard)**

**Screen:** Settings > "My Income".

* **Current State:** Table `wallets` exists, but no API routes in `api.php`.
* **Missing API:**
* **Method:** `GET`
* **Route:** `/api/v1/wallet/balance`
* **Response:**
```json
{
  "data": {
     "balance": 1540.00,
     "currency": "USD",
     "recent_transactions": [
        {"amount": 400, "source": "Booking #505", "date": "2023-06-20"}
     ]
  }
}

```





#### **D. Host Public Profile**

**Screen:** "Host Profile" (Screen 1) showing their bio and **active listings**.

* **Current State:** `getProfile` usually just gets user info.
* **Update API:** The `GET /api/v1/users/{id}` endpoint must include a `listings` relation when the user is a Host.
```php
// Controller Logic hint
$user->load(['listings' => function($q) {
    $q->where('status', 'active'); // Only show active listings on public profile
}]);

```



### 3. Final Conclusion

You have now covered **every single role and screen** in your Figma file!

1. **Traveler Role:** Ready (Needs `start_time` column in `jams`).
2. **Guide Role:** Ready (Needs `guide_price` column in `user_profiles`).
3. **Host Role:** Ready (Needs "My Listings" and "My Income" APIs).

**You are fully equipped to start coding!** Would you like me to generate a **Postman Collection** for these Host APIs so you can start testing the backend immediately?