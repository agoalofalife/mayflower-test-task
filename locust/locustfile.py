from locust import HttpUser, task, between

class ApiClients(HttpUser):
    wait_time = between(1, 1)

    @task
    def get_countries_counter(self):
        self.client.get("/api/countries")
