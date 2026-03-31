RUMIKU INTERNAL SYSTEM (SYSTEM MAP)
1. Project Overview
Company: PT. RUMI KULTURA UTOPIA (RUMIKU)

Goal: A centralized internal management tool for the holding company and its subsidiary projects (Creedigo, ROKU Project, Kyoomi, Glocult).

Target Users: 3 Core Team Members.

Hosting Environment: Shared Hosting (Hostinger Business Plan).

2. Technical Stack
Framework: Laravel 13 (Latest)

Frontend: TALL Stack (Tailwind CSS, Alpine.js, Laravel Livewire 3).

Primary Database: MySQL (Shared Hosting).

Secondary Database: Supabase (PostgreSQL) for real-time features.

Automation: n8n integration for API bridging (Webhooks for Social Media & Messaging).

3. Core Modules Roadmap
Module 1: Team Management — Refactored to Dynamic Notion-Style (Completed)

Module 2: Bookkeeping & Analytics (Completed)

Module 3: Inventory Warehouse (Skipped/Pending)

Module 4: Social Media (Completed)

Module 5: Omni-channel Communication (Completed)

Module 6: Marketing (In Progress)

4. Current Development Status
Last Update: 2026-03-31 (Dashboard Icon Fix, New 2-Color Favicon & Offline Avatars)

Current Focus: Module 6 (Marketing) - In Progress

Progress Log:

[UI/UX Modernization & Branding]
[x] Standardized Typography (Plus Jakarta Sans)
[x] Signature Lime Green (#D0F849) Implementation
[x] Harmonized Secondary Color Palette
[x] Borderless Rounded-3xl Aesthetic
[x] Descriptive Subtitles for all Modules
[x] Bento Grid Layout for Dashboard
[x] Enhanced Global Spacing & Padding (Premium Feel)
[x] Custom UI Components (x-custom-select) Implementation
[x] Boring Avatars Integration (x-boring-avatar) — Offline SVG Pool Refactor
[x] Task Peek (Slide-over) Interface Implementation
[x] Field Contrast Optimization (zinc-800/70 Fill for Dark Mode)
[x] Modern Global Scrollbar Styling (Auto-adapt Light/Dark Mode)
[x] Quick Insight Badges (Live Task Summary) Integration
[x] Dashboard SVG Icon Fix (Typo Path Correction)
[x] New 2-Color Favicon Implementation (Lime Green & Electric Cyan)
[x] Static App Title Standardization (RUMIKU Internal System)

[Module 1: Team Management — Notion-Style Refactor]
[x] User Authentication & Role Setup
[x] Original Task Management Schema & Logic
[x] Refactored to EAV Schema (properties, task_values tables)
[x] 9 Dynamic Property Types (text, number, select, multi_select, status, date, person, checkbox, url)
[x] Dynamic Column Rendering in Table View
[x] Notion-Style Kanban Board View
[x] Dynamic Slide-over Form (auto-generates inputs per property type)
[x] Header Refinement (Clean Heading & Quick Insight Badges)
[x] Minimalist Action Button (Icon-only)
[x] Modern Sorting Feature (Icon-based with multiple options)
[x] Add/Delete Custom Properties via UI
[x] Dashboard Backward Compatibility (EAV queries)

[Module 2: Bookkeeping & Analytics]
[x] Transaction Database Schema (Migration)
[x] Multi-Project Categorization (Creedigo, ROKU, Kyoomi, Glocult, Umum)
[x] Currency Masking Logic (Alpine.js & Livewire Sync)
[x] Summary Dashboard & Dual-Filtering

[Module 4: Social Media]
[x] Database Schema Planning (Accounts, Posts, Metrics)
[x] Eloquent Models (SocialAccount, SocialPost, PostMetric)
[x] Content Planner UI & Scheduler Logic

[Module 5: Omni-channel Communication]
[x] Database Schema & Eloquent Relations
[x] Webhook Controller (n8n Integration)
[x] Unified Inbox Livewire UI (Real-time Polling)
[x] Outbound Reply Messaging Logic

[Module 6: Marketing]
[x] Database Schema & Eloquent Models (Customers, Campaigns)
[ ] CRM Dashboard UI (Concept)

5. Development Guidelines
Efficiency: Keep the code lightweight for shared hosting performance.
Architecture: Use Laravel standard directory structures and Full-page Livewire Components.
Automation: Use n8n as a webhook bridge to standardize inbound messages from various platforms.

6. Database Schema Summary
roles: id, name, timestamps
users: id, name, email, password, role_id (FK), timestamps
tasks: id, title, created_by (FK/null → users), timestamps
properties: id, name, slug (unique), type, icon, options (json/null), sort_order, is_default, timestamps
task_values: id, task_id (FK → tasks), property_id (FK → properties), value (text/null), timestamps [unique: task_id+property_id]
transactions: id, transaction_date, type, project, amount, description, user_id (FK), timestamps

social_accounts: id, project, platform, account_name, account_handle, access_token, timestamps
social_posts: id, social_account_id (FK), user_id (FK), content_body, media_path, scheduled_at, status, timestamps

channels: id, project (string), platform (wa/ig/fb/tiktok/email), provider_id, status, timestamps
conversations: id, channel_id (FK), external_contact_id, contact_name, last_message, timestamps
messages: id, conversation_id (FK), user_id (FK/null), body, type (text/image/file), direction (inbound/outbound), timestamps

customers: id, name, email, phone, project_origin (string), status (active, inactive), timestamps
campaigns: id, title, type (wa/email), content_body, segment_filters (json), status (draft, scheduled, sent), scheduled_at, timestamps