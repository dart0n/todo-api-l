# Todo API

## Features
- User authentication with tokens
- Users can manage projects and tasks

## API endpoints
| HTTP   | Endpoint |       |
| :----- | :------- | :---- |
| POST   | api/v1/register                        | Create user
| POST   | api/v1/login                           | Log in user
| POST   | api/v1/logout                          | Log out user
| GET    | api/v1/projects                        | Get a list of projects with tasks
| POST   | api/v1/projects                        | Create project
| GET    | api/v1/projects/{project}              | Show project
| PUT    | api/v1/projects/{project}              | Edit project
| DELETE | api/v1/projects/{project}              | Delete project
| POST   | api/v1/projects/{project}/tasks        | Create task
| PUT    | api/v1/projects/{project}/tasks/{task} | Edit task
| DELETE | api/v1/projects/{project}/tasks/{task} | Delete task
