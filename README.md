# contactManagerGroup6Fall2025
# Workflow
1. Make a branch that you do your work in
2. Push your branch to origin
3. Then pull request
4. Then Merge(hopefully no merge conflicts)


This project uses **Docker Compose** for local testing. Follow the steps below to set it up.

---

## 1. Install Dependencies
- Install [Docker Desktop for Windows](https://www.docker.com/products/docker-desktop/).
  - During setup, enable **WSL2 backend** (recommended).
- Install [Git for Windows](https://git-scm.com/download/win).
  - When prompted, choose **"Checkout as-is, commit Unix-style line endings"**.

---

## 2. Clone the Repository
Open PowerShell or WSL and run:
```bash
git clone https://github.com/your-org/your-repo.git
cd your-repo

```
## 3. Start the containers
```bash
docker compose up --build
```
Once running, visit the app at:
👉 http://localhost:8080

^^ For API testing locally, you will use this Domain.

## 4. Stopping the Containers
docker compose down

## 5 Trouble Shooting
File Permissions (Apache): If you see permission issues on Windows, add this to the web service in docker-compose.yml:
