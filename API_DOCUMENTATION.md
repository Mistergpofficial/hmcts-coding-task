API Documentation
This document describes the API endpoints for the HMCTS Task Management System.
All endpoints return JSON responses and follow standard REST conventions.

Base URL (local development):

Code
http://localhost:8000/api
Endpoints Overview
Method	Endpoint	Description
POST	/tasks	Create a new task
GET	/tasks	Retrieve all tasks
GET	/tasks/{id}	Retrieve a single task
PATCH	/tasks/{id}	Update a task
PATCH	/tasks/{id}/status	Update only the task status
DELETE	/tasks/{id}	Delete a task


1. Create Task
POST /tasks
Request Body
json
{
  "title": "Review case bundle",
  "description": "Review evidence for case 123",
  "status": "pending",
  "due_at": "2026-04-20T10:00:00"
}
Validation Rules
Field	Type	Required	Notes
title	string	yes	max 255 characters
description	string	no	optional
status	string	yes	pending, in_progress, completed
due_at	datetime	yes	ISO format recommended


Example Response (201 Created)
json
{
  "id": 1,
  "title": "Review case bundle",
  "description": "Review evidence for case 123",
  "status": "pending",
  "due_at": "2026-04-20T10:00:00",
  "created_at": "2026-04-14T12:00:00",
  "updated_at": "2026-04-14T12:00:00"
}
2. Retrieve All Tasks
GET /tasks
Example Response (200 OK)
json
[
  {
    "id": 1,
    "title": "Review case bundle",
    "status": "pending",
    "due_at": "2026-04-20T10:00:00",
    "description": "Review evidence for case 123"
  },
  {
    "id": 2,
    "title": "Prepare hearing notes",
    "status": "in_progress",
    "due_at": "2026-04-18T09:00:00",
    "description": null
  }
]
3. Retrieve a Single Task
GET /tasks/{id}
Example Response (200 OK)
json
{
  "id": 1,
  "title": "Review case bundle",
  "description": "Review evidence for case 123",
  "status": "pending",
  "due_at": "2026-04-20T10:00:00"
}
Error Response (404 Not Found)
json
{
  "message": "Task not found"
}
4. Update a Task
PATCH /tasks/{id}
Request Body (any field may be included)
json
{
  "title": "Updated title",
  "description": "Updated description",
  "status": "completed",
  "due_at": "2026-04-22T14:00:00"
}
Example Response (200 OK)
json
{
  "id": 1,
  "title": "Updated title",
  "description": "Updated description",
  "status": "completed",
  "due_at": "2026-04-22T14:00:00"
}
5. Update Task Status Only
PATCH /tasks/{id}/status
Request Body
json
{
  "status": "completed"
}
Example Response (200 OK)
json
{
  "id": 1,
  "status": "completed"
}
6. Delete a Task
DELETE /tasks/{id}
Example Response (204 No Content)
Code
(no body)
Error Response (404 Not Found)
json
{
  "message": "Task not found"
}
Error Handling
The API returns standard Laravel validation errors.

Example (422 Unprocessable Entity)
json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title field is required."],
    "status": ["The status field is required."],
    "due_at": ["The due at field is required."]
  }
}
Database Schema
Column	Type	Notes
id	bigint	primary key
title	string	required
description	text	nullable
status	string	pending, in_progress, completed
due_at	datetime	required
created_at	timestamp	auto
updated_at	timestamp	auto


Testing
Feature tests are included in:

Code
tests/Feature/TaskApiTest.php
Run tests:

bash
php artisan test