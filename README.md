## Comments Section API for Blog Post

## Instalation

Run the database migration and seed.

## Endpoints

- Show Comments
-- URL = /api/comments/
-- Method = GET

- Create a comment
-- URL = /api/comments/
-- Method = POST
-- Form fields:
---- name => string
---- message => string

- Update a comment
-- URL = /api/comments/{id}
-- Method = PATCH
-- Form fields:
---- name => string
---- message => string

- Reply to comment
-- URL = /api/comments/{id}/reply
-- Method = POST
-- Form fields:
---- name => string
---- message => string

- Delete a comment
-- URL = /api/comments/{id}
-- Method = DELETE

## UNIT Tests

Tests for Insert, Show, Update and Database system.