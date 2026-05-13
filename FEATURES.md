# Orphanage App — Feature Tracker

> **Stack:** Laravel 12, Livewire/Volt, Flux UI, Tailwind CSS, MySQL  
> **Last reviewed:** 2026-05-13

---

## Legend

| Status | Meaning |
|--------|---------|
| ✅ Done | Fully implemented and working |
| 🔧 Partial | Core scaffolding exists, needs polish or missing edge cases |
| 🔲 Planned | Agreed to build, not yet started |
| 💡 Idea | Candidate feature, not yet prioritized |

---

## 1. Authentication & Access Control

| Feature | Status | Notes |
|---------|--------|-------|
| Email/password login | ✅ Done | Laravel Fortify |
| User registration | ✅ Done | |
| Password reset via email | ✅ Done | |
| Two-factor authentication (2FA) | ✅ Done | TOTP + recovery codes |
| Email verification | ✅ Done | |
| Role-based access control | ✅ Done | Roles: admin, head_of_schools, head_of_homes, head_of_operations, caregiver, nurse, teacher, volunteer |
| Profile & avatar management | ✅ Done | |
| Account deletion | ✅ Done | |
| Appearance / theme settings | ✅ Done | |
| Session management (view/revoke) | 💡 Idea | Let users see active sessions |
| Password strength enforcement | 💡 Idea | Add policy rules on registration |
| Activity-based auto-logout | 💡 Idea | Idle timeout for security |

---

## 2. Dashboard

| Feature | Status | Notes |
|---------|--------|-------|
| Key metrics cards (children, staff, volunteers, donors) | ✅ Done | |
| 12-month admission trend chart | ✅ Done | |
| 12-month donation trend chart | ✅ Done | |
| Recent activity feed | ✅ Done | |
| Pending maintenance alerts | ✅ Done | |
| This-month donation total | ✅ Done | |
| Unread notifications panel | ✅ Done | |
| Dashboard quick-stats API (`/api/dashboard/stats`) | ✅ Done | |
| Occupancy rate widget | 💡 Idea | Beds used vs. total capacity |
| Upcoming birthdays widget | 💡 Idea | Children with birthdays in next 7 days |
| Upcoming shifts widget | 💡 Idea | Today's / tomorrow's scheduled staff |
| Overdue maintenance count widget | 💡 Idea | Surface urgency at a glance |

---

## 3. Children Management

| Feature | Status | Notes |
|---------|--------|-------|
| Full CRUD (create, view, edit, delete) | ✅ Done | |
| Access restricted to admin + head roles | ✅ Done | Head of Schools, Head of Homes, Head of Operations |
| Demographics (name, gender, DOB, guardianship) | ✅ Done | |
| Profile photo upload | ✅ Done | |
| Medical info (blood group, height, weight, special needs) | ✅ Done | |
| Admission tracking (source, guardian info) | ✅ Done | |
| Education history records | ✅ Done | |
| Talents & interests tracking | ✅ Done | Categories: Art, Music, Sports, Academics, Technical, Social |
| Development milestones | ✅ Done | Types: Growth, Achievement, Medical, Behavioral |
| Physical measurements history | ✅ Done | |
| Room assignment | ✅ Done | |
| Child profile page | ✅ Done | |
| Search & filter | ✅ Done | |
| Age auto-calculated from DOB | ✅ Done | |
| Admission log (intake details) | ✅ Done | `AdmissionLog` model |
| Case notes / daily journal | 💡 Idea | Staff notes per child per day |
| Behavioral incident reports | 💡 Idea | Track and review behavioral events |
| Medical appointment scheduling | 💡 Idea | Link to a calendar or reminder |
| Discharge / reunion tracking | 💡 Idea | Record when a child leaves the facility |
| Guardian portal access | 💡 Idea | Read-only portal for registered guardians |
| CSV bulk import | 💡 Idea | Batch-add children from spreadsheet |

---

## 4. Staff Management

| Feature | Status | Notes |
|---------|--------|-------|
| Full CRUD | ✅ Done | |
| Role assignment | ✅ Done | Roles: admin, Head of Schools, Head of Homes, Head of Operations, caregiver, nurse, teacher |
| Position, department, employment type | ✅ Done | |
| Hire date & salary records | ✅ Done | |
| Qualifications & emergency contacts | ✅ Done | |
| Shift scheduling | ✅ Done | |
| Shift hours calculation | ✅ Done | |
| Staff profile view | ✅ Done | |
| Leave / absence tracking | 💡 Idea | Record approved leave, sick days |
| Performance reviews | 💡 Idea | Periodic review cycles with scores |
| Training & certification tracker | 💡 Idea | Expiry reminders for certs |
| Staff availability calendar | 💡 Idea | Visual weekly/monthly schedule |
| Payroll export | 💡 Idea | Generate payroll summary CSV/PDF |

---

## 5. Volunteer Management

| Feature | Status | Notes |
|---------|--------|-------|
| Volunteer registration | ✅ Done | |
| Status workflow (pending → approved / suspended / inactive) | ✅ Done | |
| Background check tracking | ✅ Done | |
| Skills & availability documentation | ✅ Done | |
| Task assignment & tracking | ✅ Done | |
| Task completion tracking | ✅ Done | |
| Volunteer rating/performance | ✅ Done | |
| Weekly & monthly task statistics | ✅ Done | |
| Approval workflow | ✅ Done | |
| Volunteer hours log | 💡 Idea | Accumulate total hours for certificates |
| Volunteer portal (self-service) | 💡 Idea | Volunteers see their own tasks & history |
| Email confirmation on approval | 🔲 Planned | Trigger email when status changes to approved |

---

## 6. Circle of Friends (Donors)

| Feature | Status | Notes |
|---------|--------|-------|
| Full CRUD | ✅ Done | |
| Access restricted to Administrator only | ✅ Done | Route middleware + sidebar guard |
| Member types (individual, organization, corporate) | ✅ Done | |
| Tax ID tracking | ✅ Done | |
| Contribution history | ✅ Done | |
| Status management (active, inactive, preferred) | ✅ Done | |
| Contribution frequency analysis | ✅ Done | |
| Annual & all-time summaries | ✅ Done | |
| Receipt number generation | ✅ Done | |
| Contribution types (cash, material, services) | ✅ Done | |
| Contribution status (pledged, received, cancelled) | ✅ Done | |
| Member search API | ✅ Done | Admin-only |
| Contribution stats API | ✅ Done | Admin-only |
| Automated contribution receipt PDF email | 🔲 Planned | Send receipt to member on `received` status |
| Online contribution form (public) | 💡 Idea | Public-facing form with payment gateway |
| Recurring contribution tracking | 💡 Idea | Monthly pledge management |
| Thank-you letter generation | 💡 Idea | Templated letter for year-end giving |
| Campaign / fund tagging | 💡 Idea | Earmark contributions to specific projects |

---

## 7. Facilities Management

| Feature | Status | Notes |
|---------|--------|-------|
| Full CRUD | ✅ Done | |
| Facility types (dormitory, classroom, kitchen, clinic, office, recreation, storage) | ✅ Done | |
| Capacity & active/inactive status | ✅ Done | |
| Facility manager assignment | ✅ Done | |
| Bed availability & occupancy rate | ✅ Done | |
| Room management within facilities | ✅ Done | |
| Maintenance request association | ✅ Done | |
| Facility search API | ✅ Done | |
| Facility inspection / condition log | 💡 Idea | Regular inspection records |
| Asset inventory per facility | 💡 Idea | Equipment and furniture tracking |
| Facility map / floor plan upload | 💡 Idea | Attach a layout image |

---

## 8. Room Allocation

| Feature | Status | Notes |
|---------|--------|-------|
| Room CRUD | ✅ Done | |
| Room number & bed count | ✅ Done | |
| Occupancy tracking (used vs. available) | ✅ Done | |
| Child-to-room assignment with dates | ✅ Done | |
| Unassign child from room | ✅ Done | |
| Assignment history | ✅ Done | `assigned_date` / `unassigned_date` |
| Occupancy rate calculations | ✅ Done | |
| Assignment notes | ✅ Done | |
| Visual room map / grid view | 💡 Idea | See all beds in one grid |
| Bed-level tracking | 💡 Idea | Specific bed within a room |

---

## 9. Maintenance Requests

| Feature | Status | Notes |
|---------|--------|-------|
| Full CRUD | ✅ Done | |
| Priority levels (low, medium, high, urgent) | ✅ Done | |
| Status workflow (pending → in_progress → completed / cancelled) | ✅ Done | |
| Cost estimation & actual cost tracking | ✅ Done | |
| Request & due date management | ✅ Done | |
| Staff assignment for repairs | ✅ Done | |
| Resolution documentation | ✅ Done | |
| Overdue detection | ✅ Done | |
| Maintenance stats API | ✅ Done | |
| Email/notification on assignment | 🔲 Planned | Notify assigned staff when task assigned |
| Recurring / preventive maintenance schedules | 💡 Idea | Auto-create requests on a schedule |
| Maintenance cost reports | 💡 Idea | Monthly/annual breakdown by facility |
| Vendor / contractor tracking | 💡 Idea | External service providers |
| Photo attachments on requests | 💡 Idea | Before/after photos of repairs |

---

## 10. Document Management

| Feature | Status | Notes |
|---------|--------|-------|
| File upload & storage | ✅ Done | |
| Document types (document, photo, medical_record, etc.) | ✅ Done | |
| Tags & descriptions | ✅ Done | |
| Visibility controls (public / private) | ✅ Done | |
| Polymorphic relations (attach to child, donor, etc.) | ✅ Done | |
| File download | ✅ Done | |
| Uploader tracking | ✅ Done | |
| Document expiry / renewal reminders | 💡 Idea | Flag docs with an expiry date |
| Document version history | 💡 Idea | Track edits/replacements |
| Bulk upload | 💡 Idea | Upload multiple files at once |
| Cloud storage integration (S3) | 💡 Idea | Move from local disk to S3-compatible store |

---

## 11. Reports & Analytics

| Feature | Status | Notes |
|---------|--------|-------|
| Reports dashboard | ✅ Done | Admin-only |
| Children demographic report | ✅ Done | |
| Donations financial report | ✅ Done | |
| Staff report | ✅ Done | |
| Facilities occupancy report | ✅ Done | |
| Maintenance status report | ✅ Done | |
| Child profile PDF export | ✅ Done | |
| Donor report PDF export | ✅ Done | |
| Report export action (`/reports/export`) | 🔧 Partial | Route exists; verify CSV/Excel output |
| Scheduled automated report emails | 💡 Idea | Weekly summary emailed to admin |
| Donor tax receipt batch generation | 💡 Idea | Year-end bulk PDF for all donors |
| Custom date-range filtering on all reports | 💡 Idea | Currently some reports may be all-time |
| Chart image export (PNG) | 💡 Idea | Embed graphs in downloaded reports |

---

## 12. Notifications

| Feature | Status | Notes |
|---------|--------|-------|
| In-app notification creation | ✅ Done | `Notification` model with read/unread |
| Notification feed on dashboard | ✅ Done | |
| Notifications API (`/api/notifications`) | ✅ Done | |
| Sender tracking | ✅ Done | |
| Actual email delivery | 🔲 Planned | Wire Laravel Mail to notification events |
| Real-time push (WebSockets / Echo) | 💡 Idea | Live bell icon without page refresh |
| SMS notifications | 💡 Idea | Twilio or similar for urgent alerts |
| Notification preferences per user | 💡 Idea | Let users opt in/out of categories |

---

## 13. Audit Logging

| Feature | Status | Notes |
|---------|--------|-------|
| Action tracking (create, update, delete) | ✅ Done | |
| Model type & ID recording | ✅ Done | |
| Old & new value diffing | ✅ Done | |
| IP address & user agent logging | ✅ Done | |
| User accountability | ✅ Done | |
| Audit log viewer UI | 🔧 Partial | Model and logging exist; verify if there's an admin view |
| Audit log export | 💡 Idea | CSV export for compliance |
| Retention policy / auto-purge | 💡 Idea | Auto-delete logs older than N months |

---

## 14. Public / External Interfaces

| Feature | Status | Notes |
|---------|--------|-------|
| Public landing page | ✅ Done | `welcome.blade.php` |
| Online donation form | 💡 Idea | With Stripe/PayPal integration |
| Volunteer sign-up form (public) | 💡 Idea | Self-register without admin invitation |
| Child sponsorship portal | 💡 Idea | Sponsors track their sponsored child |
| Annual report / impact page | 💡 Idea | Public-facing stats for transparency |

---

## 15. Infrastructure & Developer Experience

| Feature | Status | Notes |
|---------|--------|-------|
| Laravel Sail (Docker dev environment) | ✅ Done | |
| Vite asset bundling | ✅ Done | |
| Queue jobs table | ✅ Done | Ready for background jobs |
| Cache table | ✅ Done | |
| Error pages (403, 404, 500) | ✅ Done | |
| Feature tests | 💡 Idea | PHPUnit coverage for core flows |
| CI pipeline (GitHub Actions) | 💡 Idea | Run tests + lint on every push |
| `.env` validation on boot | 💡 Idea | Fail fast if required vars are missing |
| API authentication (Sanctum/Passport) | 💡 Idea | If a mobile app is planned |
| Rate limiting on API routes | 💡 Idea | Prevent abuse of search/stats endpoints |

---

---

## 16. Staff Reports Module

| Feature | Status | Notes |
|---------|--------|-------|
| Create report (typed content) | ✅ Done | Rich textarea with monospace display |
| Create report (file upload) | ✅ Done | PDF, Word, Excel, TXT — stored on private disk |
| Create report (both content + file) | ✅ Done | |
| Report types | ✅ Done | Weekly, Monthly, Quarterly, Annual, Incident, Other |
| Report classification | ✅ Done | Child Welfare, Facility, Financial, Staff, Volunteer, Operational, Other |
| Report period (start / end dates) | ✅ Done | |
| Save as draft | ✅ Done | Staff can revise before submitting |
| Submit for approval | ✅ Done | On create or edit form, or via standalone submit action |
| Staff can only view their own reports | ✅ Done | Controller enforces ownership check |
| Approved reports are locked (no edit) | ✅ Done | `isEditable()` blocks edit/update for approved reports |
| Staff can delete own draft reports | ✅ Done | |
| Head of Operations sees ALL reports | ✅ Done | Role check in controller + "All Reports" sidebar label |
| Admin sees ALL reports | ✅ Done | |
| Approve report + optional note | ✅ Done | |
| Reject report + required reason | ✅ Done | |
| Notification to staff on approve | ✅ Done | Uses existing `Notification` model |
| Notification to staff on reject | ✅ Done | |
| Pending review badge on sidebar | ✅ Done | Amber counter shown to Admin and Head of Operations |
| File download (secured) | ✅ Done | Private disk, ownership/role checked |
| Filter by type, classification, status | ✅ Done | |
| Stats cards on index (pending, approved, drafts, total) | ✅ Done | |
| Re-submit after rejection | ✅ Done | Rejected reports can be edited and re-submitted |
| Email notification on approve/reject | 🔲 Planned | Wire Laravel Mail to notification events |
| Rich text editor for content | 💡 Idea | Replace plain textarea with TipTap or Quill |
| Report comments / discussion thread | 💡 Idea | Back-and-forth notes between reviewer and staff |
| Bulk approve | 💡 Idea | Admin batch-approve multiple submitted reports |
| Report templates | 💡 Idea | Pre-filled structures for weekly/monthly reports |

---

## Backlog Priority Queue

Use this section to order items for the next sprint.

| Priority | Feature | Module |
|----------|---------|--------|
| 1 | Email notifications on key events | Notifications |
| 2 | Automated donation receipt PDF email | Donors |
| 3 | Email/notification on maintenance assignment | Maintenance |
| 4 | Email on volunteer approval | Volunteers |
| 5 | Audit log admin viewer UI | Audit |
| 6 | Report export (CSV/Excel) verification | Reports |
| 7 | Case notes / daily journal for children | Children |
| 8 | Volunteer hours log | Volunteers |
| 9 | Online donation form with payment gateway | Public |
| 10 | Feature/integration tests | DevX |
