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
If this is first time running docker run this instead and you only have to do this once
```bash
docker compose up build --no-cache
```
To start your containers run
```bash
docker compose up -d
```
This will run your containers in detacted mode

Once running, visit the app at:
👉 http://localhost:8080

^^ For API testing locally, you will use this Domain.Everywhere you see http://contactymanager.shop/LAMPAPI will need to be changed to http://localhost:8080/LAMPAPI 

## 4. Stopping the Containers
docker compose down

## 5. Trouble Shooting
File Permissions (Apache): If you see permission issues on Windows, add this to the web service in docker-compose.yml:

## 6. Useful commands

If you want to add sql data to the testdb use
```bash
docker compose exec db bash
```
This command will execute the bash program in db(the container)

Then run this command to enter test database
```bash
mysql -u root -p
```
mysql will ask for a password for the user "root" which is in the docker-compose.yml file
```bash
rootpass
```
Now you should be in the database where you can run whatever commands you need.
### Typical Work flow for testing API
1. Run containers
   ```bash
   docker compose up -d
   ```
2. Test API using curl or postman(on local host postman has extra steps)
3. To check if it changed within data base refernce "if you want to add sql data "
4. Bring down containers when finished
   ```bash
   docker compose down
   ```
Now you are a docker pro.
